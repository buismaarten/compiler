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

    private function visit(AbstractNode $node): void
    {
        // @phpstan-ignore-next-line
        match ($node::class) {
            Statements\ConstStatement::class    => $this->visitConstStatement($node),
            Statements\FunctionStatement::class => $this->visitFunctionStatement($node),
            Statements\IfStatement::class       => $this->visitIfStatement($node),
            Statements\VarStatement::class      => $this->visitVarStatement($node),
            Statements\WhileStatement::class    => $this->visitWhileStatement($node),
        };
    }

    private function visitConstStatement(Statements\ConstStatement $node): void
    {
        // TODO
    }

    private function visitFunctionStatement(Statements\FunctionStatement $node): void
    {
        $this->traverse($node->statements);
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

    private function visitWhileStatement(Statements\WhileStatement $node): void
    {
        $this->traverse($node->statements);
    }
}
