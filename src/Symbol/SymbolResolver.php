<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions;
use Package\Nodes\Statements;

final class SymbolResolver
{
    private ?Scope $scope = null;

    /** @var AbstractNode[] */
    private array $nodes;

    /** @param AbstractNode[] $nodes */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    public function resolve(AbstractNode $node): AbstractNode
    {
        $this->pushScope();

        foreach ($this->nodes as $statement) {
            $this->visit($statement);
        }

        if ($node instanceof Expressions\IdentifierExpression) {
            $resolved = $this->scope?->find($node->name) ?? $node;
            $this->popScope();

            return $resolved;
        }

        $this->popScope();

        return $node;
    }

    /** @param AbstractNode[] $nodes */
    private function traverse(array $nodes): void
    {
        $this->pushScope();

        foreach ($nodes as $node) {
            $this->visit($node);
        }

        $this->popScope();
    }

    private function visit(AbstractNode $node): void
    {
        match ($node::class) {
            Statements\ConstStatement::class => $this->visitConstStatement($node),
            Statements\IfStatement::class    => $this->visitIfStatement($node),
            Statements\VarStatement::class   => $this->visitVarStatement($node),

            // Fallback
            default => null,
        };
    }

    private function visitIfStatement(Statements\IfStatement $node): void
    {
        $this->traverse($node->thenStatements);
        $this->traverse($node->elseStatements);
    }

    private function visitVarStatement(Statements\VarStatement $node): void
    {
        $this->scope?->define($node->name, $node);
    }

    private function visitConstStatement(Statements\ConstStatement $node): void
    {
        $this->scope?->define($node->name, $node);
    }

    private function pushScope(): void
    {
        $this->scope = new Scope($this->scope);
    }

    private function popScope(): void
    {
        $this->scope = $this->scope?->parent;
    }
}
