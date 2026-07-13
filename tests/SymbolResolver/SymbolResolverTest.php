<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\SymbolResolver;

use Package\Exceptions\SymbolResolver as Exceptions;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\SymbolResolver\SymbolResolver;
use Package\Tests\Testing\SnapshotAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

// TODO: add tests for duplicate symbol
class SymbolResolverTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testResolveAssignmentExpression(): void
    {
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\AssignmentExpression(
            location: $location,
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: $location,
                value: 1,
            ),
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\VarStatement(
                    location: $location,
                    name: 'a',
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: $location,
                        value: 1,
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($root, $node);
    }

    #[Test]
    public function testResolveAssignmentExpressionUndefinedSymbol(): void
    {
        $this->expectException(Exceptions\UndefinedSymbolException::class);
        $this->expectExceptionMessageIs("Undefined symbol 'a' on line -1");

        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\AssignmentExpression(
            location: $location,
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: $location,
                value: 1,
            ),
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $resolver = new SymbolResolver($root);
        $resolver->resolve($node);
    }

    #[Test]
    public function testResolveFunctionCallExpression(): void
    {
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\FunctionCallExpression(
            location: $location,
            name: 'foo',
            arguments: [],
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\FunctionStatement(
                    location: $location,
                    name: 'foo',
                    parameters: [],
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Void,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($root, $node);
    }

    #[Test]
    public function testResolveFunctionCallExpressionUndefinedSymbol(): void
    {
        $this->expectException(Exceptions\UndefinedSymbolException::class);
        $this->expectExceptionMessageIs("Undefined symbol 'foo' on line -1");

        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\FunctionCallExpression(
            location: $location,
            name: 'foo',
            arguments: [],
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $resolver = new SymbolResolver($root);
        $resolver->resolve($node);
    }

    #[Test]
    public function testResolveFunctionParameter(): void
    {
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\FunctionStatement(
                    location: $location,
                    name: 'foo',
                    parameters: [
                        new Parameter(
                            location: $location,
                            name: 'a',
                            type: new Types\BuiltinType(
                                location: $location,
                                type: Types\Type::Integer,
                            ),
                        ),
                    ],
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Void,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\WhileStatement(
                                location: $location,
                                condition: $node,
                                statements: new Block(
                                    location: $location,
                                    statements: [],
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($root, $node);
    }

    #[Test]
    public function testResolveIdentifierExpression(): void
    {
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\ConstStatement(
                    location: $location,
                    name: 'a',
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: $location,
                        value: 1,
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($root, $node);
    }

    #[Test]
    public function testResolveIdentifierExpressionUndefinedSymbol(): void
    {
        $this->expectException(Exceptions\UndefinedSymbolException::class);
        $this->expectExceptionMessageIs("Undefined symbol 'a' on line -1");

        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\WhileStatement(
                    location: $location,
                    condition: $node,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $resolver = new SymbolResolver($root);
        $resolver->resolve($node);
    }

    #[Test]
    public function testResolveNamedType(): void
    {
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Types\NamedType(
            location: $location,
            name: 'xyz',
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\ConstStatement(
                    location: $location,
                    name: 'xyz',
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: $location,
                        value: 1,
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: new Literals\BooleanLiteral(
                        location: $location,
                        value: true,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\FunctionStatement(
                                location: $location,
                                name: 'foo',
                                parameters: [],
                                type: $node,
                                statements: new Block(
                                    location: $location,
                                    statements: [],
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($root, $node);
    }

    #[Test]
    public function testResolveNamedTypeUndefinedSymbol(): void
    {
        $this->expectException(Exceptions\UndefinedSymbolException::class);
        $this->expectExceptionMessageIs("Undefined symbol 'xyz' on line -1");

        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $node = new Types\NamedType(
            location: $location,
            name: 'xyz',
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\WhileStatement(
                    location: $location,
                    condition: new Literals\BooleanLiteral(
                        location: $location,
                        value: true,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\FunctionStatement(
                                location: $location,
                                name: 'foo',
                                parameters: [],
                                type: $node,
                                statements: new Block(
                                    location: $location,
                                    statements: [],
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );

        $resolver = new SymbolResolver($root);
        $resolver->resolve($node);
    }

    private function assertSnapshot(AbstractNode $root, AbstractNode $node): void
    {
        $resolver = new SymbolResolver($root);

        $this->assertMatchesSnapshot($resolver->resolve($node));
    }
}
