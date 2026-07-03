<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;
use Package\Nodes\Statements;

final class SymbolResolver
{
    /** @param AbstractNode[] $nodes */
    public function traverse(array $nodes): void
    {
        $this->traverseArray($nodes);
    }

    /** @param AbstractNode[] $nodes */
    private function traverseArray(array $nodes): void
    {
        foreach ($nodes as $node) {
            $this->traverseNode($node);
        }
    }

    // TODO
    private function traverseNode(AbstractNode $node): void
    {
        // @phpstan-ignore-next-line
        match ($node::class) {
            Statements\FunctionStatement::class => $this->traverseNode($node),
            Statements\IfStatement::class       => $this->traverseNode($node),
            Statements\WhileStatement::class    => $this->traverseNode($node),
        };
    }
}
