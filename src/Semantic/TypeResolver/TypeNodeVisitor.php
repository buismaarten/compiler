<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use ArrayObject;
use Override;
use Package\Language\BinaryOperator;
use Package\Language\Primitive;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions\AbstractExpression;
use Package\Nodes\Literals\AbstractLiteral;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;
use SplStack;
use WeakMap;

final class TypeNodeVisitor extends AbstractNodeVisitor
{
    private SymbolResolver $symbolResolver;

    /** @var WeakMap<AbstractNode, AbstractType> */
    private WeakMap $types;

    /** @var WeakMap<AbstractNode, AbstractType[]> */
    private WeakMap $functionReturnTypes;

    /** @var SplStack<ArrayObject<array-key, AbstractType>> */
    private SplStack $functionReturnTypesStack;

    /**
     * @param WeakMap<AbstractNode, AbstractType> $types
     * @param WeakMap<AbstractNode, AbstractType[]> $functionReturnTypes
     */
    public function __construct(SymbolResolver $symbolResolver, WeakMap $types, WeakMap $functionReturnTypes)
    {
        $this->symbolResolver = $symbolResolver;
        $this->types = $types;
        $this->functionReturnTypes = $functionReturnTypes;
        $this->functionReturnTypesStack = new SplStack;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->enterFunctionStatement($node);
            return;
        }
    }

    private function enterFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
        $this->functionReturnTypesStack->push(new ArrayObject);
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof AbstractLiteral) {
            $this->leaveLiteral($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\ArrayExpression) {
            $this->leaveArrayExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->leaveAssignmentExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\BinaryExpression) {
            $this->leaveBinaryExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\FunctionCallExpression) {
            $this->leaveFunctionCallExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\IdentifierExpression) {
            $this->leaveIdentifierExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\TupleExpression) {
            $this->leaveTupleExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\UnaryExpression) {
            $this->leaveUnaryExpression($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->leaveConstantStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->leaveFunctionStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ReturnStatement) {
            $this->leaveReturnStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->leaveVariableStatement($node);
            return;
        }
    }

    private function leaveArrayExpression(Nodes\Expressions\ArrayExpression $node): void
    {
        $this->types[$node] = $this->resolveType($node);
    }

    private function leaveAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function leaveBinaryExpression(Nodes\Expressions\BinaryExpression $node): void
    {
        $left = $this->types[$node->left];
        $right = $this->types[$node->right];

        $this->types[$node] = $this->resolveBinaryType($node->operator, $left, $right);
    }

    private function resolveBinaryType(BinaryOperator $operator, AbstractType $left, AbstractType $right): AbstractType
    {
        if ($left instanceof Types\ErrorType || $right instanceof Types\ErrorType) {
            return new Types\ErrorType;
        }

        $nullable = false;

        while ($left instanceof Types\NullableType) {
            $nullable = true;
            $left = $left->type;
        }

        while ($right instanceof Types\NullableType) {
            $nullable = true;
            $right = $right->type;
        }

        $result = match ($operator) {
            BinaryOperator::Plus,
            BinaryOperator::Minus,
            BinaryOperator::Multiply => $this->resolveNumericResultType($left, $right),
            BinaryOperator::Divide => $this->isNumericType($left) && $this->isNumericType($right)
                ? new Types\PrimitiveType(Primitive::Float)
                : new Types\ErrorType,
            BinaryOperator::Modulo,
            BinaryOperator::BitwiseAnd,
            BinaryOperator::BitwiseOr,
            BinaryOperator::BitwiseXor,
            BinaryOperator::ShiftLeft,
            BinaryOperator::ShiftRight => $this->isIntegerType($left) && $this->isIntegerType($right)
                ? new Types\PrimitiveType(Primitive::Integer)
                : new Types\ErrorType,
            BinaryOperator::BooleanAnd,
            BinaryOperator::BooleanOr => $this->isBooleanType($left) && $this->isBooleanType($right)
                ? new Types\PrimitiveType(Primitive::Bool)
                : new Types\ErrorType,
            BinaryOperator::Equal,
            BinaryOperator::Identical,
            BinaryOperator::NotEqual,
            BinaryOperator::NotIdentical,
            BinaryOperator::GreaterThan,
            BinaryOperator::GreaterThanEqual,
            BinaryOperator::LessThan,
            BinaryOperator::LessThanEqual => new Types\PrimitiveType(Primitive::Bool),
            BinaryOperator::Spaceship => new Types\PrimitiveType(Primitive::Integer),
        };

        if (!$nullable || $result instanceof Types\ErrorType) {
            return $result;
        }

        return new Types\NullableType($result);
    }

    private function resolveNumericResultType(AbstractType $left, AbstractType $right): AbstractType
    {
        if (!$this->isNumericType($left) || !$this->isNumericType($right)) {
            return new Types\ErrorType;
        }

        if (
            ($left instanceof Types\PrimitiveType && $left->type === Primitive::Float)
            || ($right instanceof Types\PrimitiveType && $right->type === Primitive::Float)
        ) {
            return new Types\PrimitiveType(Primitive::Float);
        }

        return new Types\PrimitiveType(Primitive::Integer);
    }

    private function isNumericType(AbstractType $type): bool
    {
        return $type instanceof Types\PrimitiveType
            && in_array($type->type, [Primitive::Integer, Primitive::Float], true);
    }

    private function isIntegerType(AbstractType $type): bool
    {
        return $type instanceof Types\PrimitiveType && $type->type === Primitive::Integer;
    }

    private function isBooleanType(AbstractType $type): bool
    {
        return $type instanceof Types\PrimitiveType && $type->type === Primitive::Bool;
    }

    private function leaveConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function leaveFunctionCallExpression(Nodes\Expressions\FunctionCallExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function leaveFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $functionReturnTypes = $this->functionReturnTypesStack->pop();

        $this->functionReturnTypes[$node] = $functionReturnTypes->getArrayCopy();
    }

    private function leaveIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    /** @param AbstractLiteral<mixed> $node */
    private function leaveLiteral(AbstractLiteral $node): void
    {
        $this->types[$node] = new Types\PrimitiveType($node->getPrimitive());
    }

    private function leaveReturnStatement(Nodes\Statements\ReturnStatement $node): void
    {
        if ($node->expression !== null) {
            $type = $this->types[$node->expression];
        } else {
            $type = new Types\PrimitiveType(Primitive::Null);
        }

        $functionReturnTypes = $this->functionReturnTypesStack->top();
        $functionReturnTypes->append($type);
    }

    private function leaveTupleExpression(Nodes\Expressions\TupleExpression $node): void
    {
        $this->types[$node] = $this->resolveType($node);
    }

    private function leaveUnaryExpression(Nodes\Expressions\UnaryExpression $node): void
    {
        $type = $this->types[$node->expression];

        // TODO
        // var_dump($type);
    }

    private function leaveVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function resolveType(AbstractExpression $node): AbstractType
    {
        if ($node instanceof Nodes\Expressions\ArrayExpression) {
            $type = null;

            if ($expression = array_first($node->expressions)) {
                $type = $this->resolveType($expression);
            }

            return new Types\ArrayType($type);
        }

        if ($node instanceof Nodes\Expressions\TupleExpression) {
            $types = [];

            foreach ($node->expressions as $expression) {
                $types[] = $this->resolveType($expression);
            }

            return new Types\TupleType($types);
        }

        return $this->types[$node];
    }
}
