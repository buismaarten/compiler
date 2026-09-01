<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Override;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Types\AbstractType;
use WeakMap;

final class TypeNodeVisitor extends AbstractNodeVisitor
{
    private SymbolResolver $symbolResolver;

    /** @var WeakMap<AbstractNode, AbstractType> */
    private WeakMap $table;

    /** @param WeakMap<AbstractNode, AbstractType> $table */
    public function __construct(SymbolResolver $symbolResolver, WeakMap $table)
    {
        $this->symbolResolver = $symbolResolver;
        $this->table = $table;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->visitAssignmentExpression($node);

            return;
        }

        if ($node instanceof Nodes\Expressions\IdentifierExpression) {
            $this->visitIdentifierExpression($node);

            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->visitConstantStatement($node);

            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->visitVariableStatement($node);

            return;
        }
    }

    private function visitAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->symbolResolver->resolve($node);

        $this->table[$node] = $symbol->type;
    }

    private function visitConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        $symbol = $this->symbolResolver->resolve($node);

        $this->table[$node] = $symbol->type;
    }

    private function visitIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->symbolResolver->resolve($node);

        $this->table[$node] = $symbol->type;
    }

    private function visitVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        $symbol = $this->symbolResolver->resolve($node);

        $this->table[$node] = $symbol->type;
    }
}
