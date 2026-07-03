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

    /** @param AbstractNode[] $nodes */
    public function traverse(array $nodes): void
    {
        $this->scopeStack[] = [];

        foreach ($nodes as $node) {
            $this->visit($node);
        }

        array_pop($this->scopeStack);
    }

    // TODO
    private function visit(AbstractNode $node): void
    {
        // @phpstan-ignore-next-line
        match ($node::class) {
            Statements\FunctionStatement::class => $this->visit($node),
            Statements\IfStatement::class       => $this->visit($node),
            Statements\WhileStatement::class    => $this->visit($node),
        };
    }
}
