<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;
use Package\Nodes\Statements;

final class SymbolResolver
{
    /** @var array<int, array<string>> */
    private array $scopeStack = [];

    /** @var AbstractNode[] */
    private array $nodes;

    /** @param AbstractNode[] $nodes */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    public function resolve(AbstractNode $node): AbstractNode
    {
        $this->traverse($this->nodes);

        // TODO
        return $node;
    }

    /** @param AbstractNode[] $nodes */
    private function traverse(array $nodes): void
    {
        $this->scopeStack[] = [];

        foreach ($nodes as $node) {
            $this->visit($node);
        }

        array_pop($this->scopeStack);
    }

    private function visit(AbstractNode $node): void
    {
        match ($node::class) {
            Statements\VarStatement::class => $this->visitVarStatement($node),
            Statements\IfStatement::class  => $this->visitIfStatement($node),

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
        // TODO
    }
}
