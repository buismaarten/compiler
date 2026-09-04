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
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\BlockStatement(
                    location: Location::dummy(),
                    block: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\ExpressionStatement(
                                location: Location::dummy(),
                                expression: new Expressions\AssignmentExpression(
                                    location: Location::dummy(),
                                    name: 'foo',
                                    expression: new Literals\IntegerLiteral(
                                        location: Location::dummy(),
                                        value: 1,
                                    ),
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
    public function testResolveFunctionCallExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\FunctionStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    parameters: [
                        new Parameter(
                            location: Location::dummy(),
                            name: 'foo',
                            type: new Types\BuiltinType(
                                location: Location::dummy(),
                                kind: BuiltinTypeKind::Integer,
                            ),
                        ),
                    ],
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Integer,
                    ),
                    statements: new Block(
                        location: Location::dummy(),
                        statements: [],
                    ),
                ),
                new Statements\BlockStatement(
                    location: Location::dummy(),
                    block: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\ExpressionStatement(
                                location: Location::dummy(),
                                expression: new Expressions\FunctionCallExpression(
                                    location: Location::dummy(),
                                    name: 'foo',
                                    arguments: [
                                        new Literals\IntegerLiteral(
                                            location: Location::dummy(),
                                            value: 1,
                                        ),
                                    ],
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
    public function testResolveFunctionParameter(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\FunctionStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    parameters: [
                        new Parameter(
                            location: Location::dummy(),
                            name: 'foo',
                            type: new Types\BuiltinType(
                                location: Location::dummy(),
                                kind: BuiltinTypeKind::Integer,
                            ),
                        ),
                    ],
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Void,
                    ),
                    statements: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\BlockStatement(
                                location: Location::dummy(),
                                block: new Block(
                                    location: Location::dummy(),
                                    statements: [
                                        new Statements\ExpressionStatement(
                                            location: Location::dummy(),
                                            expression: new Expressions\IdentifierExpression(
                                                location: Location::dummy(),
                                                name: 'foo',
                                            ),
                                        ),
                                    ],
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
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\ConstantStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\BlockStatement(
                    location: Location::dummy(),
                    block: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\ExpressionStatement(
                                location: Location::dummy(),
                                expression: new Expressions\IdentifierExpression(
                                    location: Location::dummy(),
                                    name: 'foo',
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
    public function testResolveNamedType(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\ConstantStatement(
                    location: Location::dummy(),
                    name: 'xyz',
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\BlockStatement(
                    location: Location::dummy(),
                    block: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\FunctionStatement(
                                location: Location::dummy(),
                                name: 'foo',
                                parameters: [],
                                type: new Types\NamedType(
                                    location: Location::dummy(),
                                    name: 'xyz',
                                ),
                                statements: new Block(
                                    location: Location::dummy(),
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

        $this->assertMatchesSnapshot($symbolResolver->getSymbols());
    }
}
