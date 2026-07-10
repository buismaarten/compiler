<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\SymbolResolver;

use Override;
use Package\Exceptions\SymbolResolver\DuplicateSymbolException;
use Package\Exceptions\SymbolResolver\UndefinedSymbolException;
use Package\Nodes;
use Package\Nodes\AbstractNode;
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
        if ($node instanceof Nodes\Block) {
            $this->scope = new Scope($this->scope);
            return;
        }

        match ($node::class) {
            Nodes\Parameter::class                          => $this->enterParameter($node),
            Nodes\Expressions\AssignmentExpression::class   => $this->enterAssignmentExpression($node),
            Nodes\Expressions\FunctionCallExpression::class => $this->enterFunctionCallExpression($node),
            Nodes\Expressions\IdentifierExpression::class   => $this->enterIdentifierExpression($node),
            Nodes\Statements\ConstStatement::class          => $this->enterConstStatement($node),
            Nodes\Statements\FunctionStatement::class       => $this->enterFunctionStatement($node),
            Nodes\Statements\VarStatement::class            => $this->enterVarStatement($node),

            // Fallback
            default => null,
        };
    }

    private function enterAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function enterConstStatement(Nodes\Statements\ConstStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function enterFunctionCallExpression(Nodes\Expressions\FunctionCallExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function enterFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
        $this->scope = new Scope($this->scope);
    }

    private function enterIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function enterParameter(Nodes\Parameter $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function enterVarStatement(Nodes\Statements\VarStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Block) {
            $this->scope = $this->scope?->parent;
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->scope = $this->scope?->parent;
            return;
        }
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
