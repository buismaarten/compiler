<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\SymbolResolver;

use LogicException;
use Override;
use Package\Exceptions\SymbolResolver\DuplicateSymbolException;
use Package\Exceptions\SymbolResolver\UndefinedSymbolException;
use Package\Internal\Stack;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\Symbols;
use Package\Semantic\Symbols\AbstractSymbol;
use Package\Semantic\Types;
use WeakMap;

final class SymbolNodeVisitor extends AbstractNodeVisitor
{
    private ?Scope $scope = null;

    /** @var Stack<Symbols\FunctionSymbol> */
    private Stack $activeFunctionStack;

    /** @var WeakMap<AbstractNode, AbstractSymbol> */
    private WeakMap $table;

    /** @param WeakMap<AbstractNode, AbstractSymbol> $table */
    public function __construct(WeakMap $table)
    {
        $this->activeFunctionStack = new Stack;
        $this->table = $table;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Block) {
            $this->scope = new Scope($this->scope);

            return;
        }

        if ($node instanceof Nodes\Parameter) {
            $this->visitParameter($node);

            return;
        }

        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->visitAssignmentExpression($node);

            return;
        }

        if ($node instanceof Nodes\Expressions\FunctionCallExpression) {
            $this->visitFunctionCallExpression($node);

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

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->visitFunctionStatement($node);

            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->visitVariableStatement($node);

            return;
        }

        if ($node instanceof Nodes\Types\NamedType) {
            $this->visitNamedType($node);

            return;
        }
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
            $this->activeFunctionStack->pop();

            return;
        }
    }

    private function visitAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Constant,
        );
    }

    private function visitFunctionCallExpression(Nodes\Expressions\FunctionCallExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $symbol = new Symbols\FunctionSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
        );

        $this->activeFunctionStack->push($symbol);
        $this->scope[$node->name] = $symbol;
        $this->scope = new Scope($this->scope);
    }

    private function visitIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitNamedType(Nodes\Types\NamedType $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function visitParameter(Nodes\Parameter $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $symbol = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Parameter,
        );

        if (!$this->activeFunctionStack->isEmpty()) {
            $currentFunction = $this->activeFunctionStack->peek();
            $currentFunction->parameters[] = $symbol;
        }

        $this->scope[$node->name] = $symbol;
    }

    private function visitVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $this->scope[$node->name] = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Variable,
        );
    }

    private function resolve(string $name, AbstractNode $node): ?AbstractSymbol
    {
        if ($symbol = $this->scope?->resolve($name)) {
            return $this->table[$node] = $symbol;
        }

        return null;
    }

    private static function resolveType(Nodes\Types\AbstractType $node): Types\AbstractType
    {
        if ($node instanceof Nodes\Types\ArrayType) {
            return new Types\ArrayType(self::resolveType($node->type));
        }

        if ($node instanceof Nodes\Types\BuiltinType) {
            return new Types\BuiltinType($node->kind);
        }

        if ($node instanceof Nodes\Types\NamedType) {
            return new Types\NamedType($node->name);
        }

        if ($node instanceof Nodes\Types\NullableType) {
            return new Types\NullableType(self::resolveType($node->type));
        }

        if ($node instanceof Nodes\Types\TupleType) {
            $types = [];

            foreach ($node->types as $type) {
                $types[] = self::resolveType($type);
            }

            return new Types\TupleType($types);
        }

        throw new LogicException;
    }
}
