<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\SymbolResolver;

use Override;
use Package\Exceptions\SymbolResolver\DuplicateSymbolException;
use Package\Exceptions\SymbolResolver\UndefinedSymbolException;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\NodeTraverser\AbstractNodeVisitor;
use WeakMap;

final class SymbolNodeVisitor extends AbstractNodeVisitor
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

        if ($node instanceof Parameter) {
            $this->visitParameter($node);

            return;
        }

        if ($node instanceof Expressions\AssignmentExpression) {
            $this->visitAssignmentExpression($node);

            return;
        }

        if ($node instanceof Expressions\FunctionCallExpression) {
            $this->visitFunctionCallExpression($node);

            return;
        }

        if ($node instanceof Expressions\IdentifierExpression) {
            $this->visitIdentifierExpression($node);

            return;
        }

        if ($node instanceof Statements\ConstantStatement) {
            $this->visitConstantStatement($node);

            return;
        }

        if ($node instanceof Statements\FunctionStatement) {
            $this->visitFunctionStatement($node);

            return;
        }

        if ($node instanceof Statements\VariableStatement) {
            $this->visitVariableStatement($node);

            return;
        }

        if ($node instanceof Types\NamedType) {
            $this->visitNamedType($node);

            return;
        }
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Block) {
            $this->scope = $this->scope?->parent;

            return;
        }

        if ($node instanceof Statements\FunctionStatement) {
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

    private function visitConstantStatement(Statements\ConstantStatement $node): void
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
        $this->scope = new Scope($this->scope);
    }

    private function visitIdentifierExpression(Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitNamedType(Types\NamedType $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitParameter(Parameter $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = $node;
    }

    private function visitVariableStatement(Statements\VariableStatement $node): void
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

        return null;
    }
}
