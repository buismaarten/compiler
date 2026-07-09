<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\SymbolResolver;

use Override;
use Package\Exceptions\SymbolResolver\DuplicateSymbolException;
use Package\Exceptions\SymbolResolver\UndefinedSymbolException;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Statements;
use Package\NodeTraverser\AbstractNodeVisitor;
use WeakMap;

final class SymbolCollector extends AbstractNodeVisitor
{
    private ?Scope $scope = null;

    /** @var WeakMap<AbstractNode, AbstractNode> */
    private WeakMap $table;

    /** @param WeakMap<AbstractNode, AbstractNode> $table */
    public function __construct(WeakMap $table)
    {
        $this->table = $table;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Block) {
            $this->scope = new Scope($this->scope);
            return;
        }

        match ($node::class) {
            Expressions\AssignmentExpression::class   => $this->visitAssignmentExpression($node),
            Expressions\FunctionCallExpression::class => $this->visitFunctionCallExpression($node),
            Expressions\IdentifierExpression::class   => $this->visitIdentifierExpression($node),
            Statements\ConstStatement::class          => $this->visitConstStatement($node),
            Statements\FunctionStatement::class       => $this->visitFunctionStatement($node),
            Statements\VarStatement::class            => $this->visitVarStatement($node),

            // Fallback
            default => null,
        };
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Block) {
            $this->scope = $this->scope?->parent;
            return;
        }
    }

    private function visitAssignmentExpression(Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitConstStatement(Statements\ConstStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function visitFunctionCallExpression(Expressions\FunctionCallExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitFunctionStatement(Statements\FunctionStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function visitIdentifierExpression(Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitVarStatement(Statements\VarStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function resolve(string $name, AbstractNode $node): ?AbstractNode
    {
        if ($symbol = $this->scope?->resolve($name)) {
            return $this->table[$node] = $symbol;
        }

        // Fallback
        return null;
    }
}
