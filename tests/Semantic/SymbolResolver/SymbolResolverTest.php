<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\SymbolResolver;

use Package\Core\Location;
use Package\Language\BuiltinTypeKind;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Tests\Testing\SnapshotAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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

        $node = new Block(
            location: $location,
            statements: [
                new Statements\VariableStatement(
                    location: $location,
                    name: 'a',
                    type: new Types\BuiltinType(
                        location: $location,
                        kind: BuiltinTypeKind::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: $location,
                        value: 1,
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: new Expressions\AssignmentExpression(
                        location: $location,
                        name: 'a',
                        expression: new Literals\IntegerLiteral(
                            location: $location,
                            value: 1,
                        ),
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
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

        $node = new Block(
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
                                kind: BuiltinTypeKind::Integer,
                            ),
                        ),
                    ],
                    type: new Types\BuiltinType(
                        location: $location,
                        kind: BuiltinTypeKind::Integer,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: new Expressions\FunctionCallExpression(
                        location: $location,
                        name: 'foo',
                        arguments: [
                            new Literals\IntegerLiteral(
                                location: $location,
                                value: 1,
                            ),
                        ],
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
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

        $node = new Block(
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
                                kind: BuiltinTypeKind::Integer,
                            ),
                        ),
                    ],
                    type: new Types\BuiltinType(
                        location: $location,
                        kind: BuiltinTypeKind::Void,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\WhileStatement(
                                location: $location,
                                condition: new Expressions\IdentifierExpression(
                                    location: $location,
                                    name: 'a',
                                ),
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

        $this->assertSnapshot($node);
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

        $node = new Block(
            location: $location,
            statements: [
                new Statements\ConstantStatement(
                    location: $location,
                    name: 'a',
                    type: new Types\BuiltinType(
                        location: $location,
                        kind: BuiltinTypeKind::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: $location,
                        value: 1,
                    ),
                ),
                new Statements\WhileStatement(
                    location: $location,
                    condition: new Expressions\IdentifierExpression(
                        location: $location,
                        name: 'a',
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
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

        $node = new Block(
            location: $location,
            statements: [
                new Statements\ConstantStatement(
                    location: $location,
                    name: 'xyz',
                    type: new Types\BuiltinType(
                        location: $location,
                        kind: BuiltinTypeKind::Integer,
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
                                type: new Types\NamedType(
                                    location: $location,
                                    name: 'xyz',
                                ),
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

        $this->assertSnapshot($node);
    }

    private function assertSnapshot(AbstractNode $node): void
    {
        $symbolResolver = new SymbolResolver($node);

        $this->assertMatchesSnapshot($symbolResolver->getTable());
    }
}
