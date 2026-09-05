<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\SymbolResolver;

use LogicException;
use Override;
use Package\Exceptions\Semantic\SymbolResolver\DuplicateSymbolException;
use Package\Exceptions\Semantic\SymbolResolver\UndefinedSymbolException;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\Symbols;
use Package\Semantic\Symbols\AbstractSymbol;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;
use SplStack;
use WeakMap;

final class SymbolNodeVisitor extends AbstractNodeVisitor
{
    private ?Scope $scope = null;

    /** @var WeakMap<AbstractNode, AbstractSymbol> */
    private WeakMap $symbols;

    /** @var SplStack<Symbols\FunctionSymbol> */
    private SplStack $functionSymbolStack;

    /** @param WeakMap<AbstractNode, AbstractSymbol> $symbols */
    public function __construct(WeakMap $symbols)
    {
        $this->symbols = $symbols;
        $this->functionSymbolStack = new SplStack;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Block) {
            $this->enterBlock($node);
            return;
        }

        if ($node instanceof Nodes\Parameter) {
            $this->enterParameter($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\AssignmentExpression) {
            $this->enterAssignmentExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\FunctionCallExpression) {
            $this->enterFunctionCallExpression($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\IdentifierExpression) {
            $this->enterIdentifierExpression($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->enterConstantStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->enterFunctionStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->enterVariableStatement($node);
            return;
        }

        if ($node instanceof Nodes\Types\NamedType) {
            $this->enterNamedType($node);
            return;
        }
    }

    private function enterAssignmentExpression(Nodes\Expressions\AssignmentExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function enterBlock(Nodes\Block $node): void
    {
        $this->scope = new Scope($this->scope);
    }

    private function enterConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $symbol = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Constant,
        );

        $this->symbols[$node] = $symbol;
        $this->scope[$node->name] = $symbol;
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

        $symbol = new Symbols\FunctionSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
        );

        $this->functionSymbolStack->push($symbol);
        $this->symbols[$node] = $symbol;
        $this->scope[$node->name] = $symbol;
        $this->scope = new Scope($this->scope);
    }

    private function enterIdentifierExpression(Nodes\Expressions\IdentifierExpression $node): void
    {
        $symbol = $this->resolve($node->name, $node);

        if ($symbol === null) {
            throw new UndefinedSymbolException($node->getStartLine(), $node->name);
        }
    }

    private function enterNamedType(Nodes\Types\NamedType $node): void
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

        $symbol = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Parameter,
        );

        $functionSymbol = $this->functionSymbolStack->top();
        $functionSymbol->parameters[] = $symbol;

        $this->symbols[$node] = $symbol;
        $this->scope[$node->name] = $symbol;
    }

    private function enterVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        if (isset($this->scope[$node->name])) {
            throw new DuplicateSymbolException($node->getStartLine(), $node->name);
        }

        $symbol = new Symbols\ValueSymbol(
            location: $node->location,
            name: $node->name,
            type: self::resolveType($node->type),
            kind: Symbols\ValueSymbolKind::Variable,
        );

        $this->symbols[$node] = $symbol;
        $this->scope[$node->name] = $symbol;
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Block) {
            $this->leaveBlock($node);
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->leaveFunctionStatement($node);
            return;
        }
    }

    private function leaveBlock(Nodes\Block $node): void
    {
        $this->scope = $this->scope?->parent;
    }

    private function leaveFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        $this->scope = $this->scope?->parent;
        $this->functionSymbolStack->pop();
    }

    private function resolve(string $name, AbstractNode $node): ?AbstractSymbol
    {
        if ($symbol = $this->scope?->resolve($name)) {
            return $this->symbols[$node] = $symbol;
        }

        return null;
    }

    private static function resolveType(Nodes\Types\AbstractType $node): AbstractType
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
