<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use ArrayObject;
use Override;
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
        $this->resolveSymbol($node);
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
        $this->resolveSymbol($node);
    }

    private function leaveBinaryExpression(Nodes\Expressions\BinaryExpression $node): void
    {
        $left = $this->types[$node->left];
        $right = $this->types[$node->right];

        // TODO
        // var_dump($left);
        // var_dump($right);
    }

    private function leaveConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        $this->resolveSymbol($node);
    }

    private function leaveFunctionCallExpression(Nodes\Expressions\FunctionCallExpression $node): void
    {
        $this->resolveSymbol($node);
    }

    private function leaveFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $functionReturnTypes = $this->functionReturnTypesStack->pop();

        $this->functionReturnTypes[$node] = $functionReturnTypes->getArrayCopy();
    }

    private function leaveIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $this->resolveSymbol($node);
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
        $this->resolveSymbol($node);
    }

    private function resolveSymbol(AbstractNode $node): void
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
