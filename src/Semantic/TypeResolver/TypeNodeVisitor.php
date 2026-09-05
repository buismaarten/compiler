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
        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->enterAssignmentExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\FunctionCallExpression) {
            $this->enterFunctionCallExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\IdentifierExpression) {
            $this->enterIdentifierExpression($node);
            return;
        }

        if ($node instanceof Nodes\Literals\AbstractLiteral) {
            $this->enterAbstractLiteral($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->enterConstantStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->enterFunctionStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->enterVariableStatement($node);
            return;
        }
    }

    private function enterAbstractLiteral(Nodes\Literals\AbstractLiteral $node): void
    {
        $this->types[$node] = new Types\BuiltinType($node->getBuiltinTypeKind());
    }

    private function enterAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function enterConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function enterFunctionCallExpression(Nodes\Expressions\FunctionCallExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function enterFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->functionReturnTypesStack->push(new ArrayObject);
        $this->types[$node] = $symbol->type;
    }

    private function enterIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    private function enterVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        $symbol = $this->symbolResolver->resolveSymbol($node);

        $this->types[$node] = $symbol->type;
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->leaveFunctionStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ReturnStatement) {
            $this->leaveReturnStatement($node);
            return;
        }
    }

    private function leaveFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $functionReturnTypes = $this->functionReturnTypesStack->pop();

        $this->functionReturnTypes[$node] = $functionReturnTypes->getArrayCopy();
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
}
