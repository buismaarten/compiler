<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use ArrayObject;
use Override;
use Package\Language\BuiltinTypeKind;
use Package\Nodes;
use Package\Nodes\AbstractNode;
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

        $this->functionReturnTypesStack->push(new ArrayObject);
        $this->types[$node] = $symbol->type;
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Expressions\BinaryExpression) {
            $this->leaveBinaryExpression($node);
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

        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->leaveAssignmentExpression($node);
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

        if ($node instanceof Nodes\Literals\AbstractLiteral) {
            $this->leaveAbstractLiteral($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->leaveConstantStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->leaveVariableStatement($node);
            return;
        }
    }

    private function leaveAbstractLiteral(Nodes\Literals\AbstractLiteral $node): void
    {
        $this->types[$node] = new Types\BuiltinType($node->getBuiltinTypeKind());
    }

    private function leaveAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function leaveBinaryExpression(Nodes\Expressions\BinaryExpression $node): void
    {
        $leftSymbol = $this->types[$node->left];
        $rightSymbol = $this->types[$node->right];

        // TODO
        var_dump($leftSymbol);
        var_dump($rightSymbol);
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

    private function leaveReturnStatement(Nodes\Statements\ReturnStatement $node): void
    {
        if ($node->expression === null) {
            $type = new Types\BuiltinType(BuiltinTypeKind::Null);
        } else {
            $type = $this->types[$node->expression];
        }

        $functionReturnTypes = $this->functionReturnTypesStack->top();
        $functionReturnTypes->append($type);
    }

    private function leaveVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }
}
