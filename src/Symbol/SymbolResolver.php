<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions;
use Package\Nodes\Statements;
use RuntimeException;
use SplObjectStorage;

final class SymbolResolver
{
    private ?Scope $scope = null;

    /** @var SplObjectStorage<AbstractNode, AbstractNode> */
    private SplObjectStorage $table;

    /** @var AbstractNode[] */
    private array $nodes;

    /** @param AbstractNode[] $nodes */
    public function __construct(array $nodes)
    {
        $this->table = new SplObjectStorage;
        $this->nodes = $nodes;
    }

    public function resolve(AbstractNode $node): AbstractNode
    {
        if (count($this->table) === 0) {
            $this->traverse($this->nodes);
        }

        // TODO
        return $this->table[$node];
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
            Expressions\IdentifierExpression::class => $this->visitIdentifierExpression($node),
            Statements\IfStatement::class           => $this->visitIfStatement($node),
            Statements\PrintStatement::class        => $this->visitPrintStatement($node),
            Statements\VarStatement::class          => $this->visitVarStatement($node),

            // Fallback
            default => null,
        };
    }

    // TODO
    private function visitIdentifierExpression(Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->findSymbol($node->name, $node);

        if ($symbol === null) {
            throw new RuntimeException(sprintf('Undefined symbol "%s"', $node->name));
        }
    }

    private function visitIfStatement(Statements\IfStatement $node): void
    {
        $this->traverse($node->thenStatements);
        $this->traverse($node->elseStatements);
    }

    private function visitPrintStatement(Statements\PrintStatement $node): void
    {
        $this->visit($node->expression);
    }

    private function visitVarStatement(Statements\VarStatement $node): void
    {
        if ($this->scope !== null) {
            $this->scope->symbols[$node->name] = $node;
        }
    }

    private function findSymbol(string $name, AbstractNode $node): ?AbstractNode
    {
        $scope = $this->scope;

        while ($scope !== null) {
            $symbol = ($scope->symbols[$name] ?? null);
            $scope = $scope->parent;

            if ($symbol !== null) {
                return $this->table[$node] = $symbol;
            }
        }

        // Fallback
        return null;
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
