<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver;

use Package\Common\Language\BinaryOperator;
use Package\Common\Language\Primitive;
use Package\Common\Language\UnaryOperator;
use Package\Common\Location;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\TypeResolver\TypeResolver;
use Package\Tests\Testing\SnapshotAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testResolveArrayExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\ArrayType(
                        location: Location::dummy(),
                        type: new Types\PrimitiveType(
                            location: Location::dummy(),
                            type: Primitive::Integer,
                        ),
                    ),
                    expression: new Expressions\ArrayExpression(
                        location: Location::dummy(),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: Location::dummy(),
                                value: 1,
                            ),
                            new Literals\IntegerLiteral(
                                location: Location::dummy(),
                                value: 2,
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveAssignmentExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\AssignmentExpression(
                        location: Location::dummy(),
                        name: 'foo',
                        expression: new Literals\IntegerLiteral(
                            location: Location::dummy(),
                            value: 2,
                        ),
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveBinaryExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Float,
                    ),
                    expression: new Literals\FloatLiteral(
                        location: Location::dummy(),
                        value: 3.14,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\BinaryExpression(
                        location: Location::dummy(),
                        operator: BinaryOperator::Plus,
                        left: new Expressions\IdentifierExpression(
                            location: Location::dummy(),
                            name: 'foo',
                        ),
                        right: new Literals\IntegerLiteral(
                            location: Location::dummy(),
                            value: 2,
                        ),
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveConstantStatement(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\ConstantStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\IdentifierExpression(
                        location: Location::dummy(),
                        name: 'foo',
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
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::String,
                    ),
                    parameters: [
                        new Parameter(
                            location: Location::dummy(),
                            name: 'bar',
                            type: new Types\PrimitiveType(
                                location: Location::dummy(),
                                type: Primitive::String,
                            ),
                        ),
                    ],
                    statements: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\ReturnStatement(
                                location: Location::dummy(),
                                expression: new Expressions\IdentifierExpression(
                                    location: Location::dummy(),
                                    name: 'bar',
                                ),
                            ),
                            new Statements\ReturnStatement(
                                location: Location::dummy(),
                                expression: new Literals\StringLiteral(
                                    location: Location::dummy(),
                                    value: 'Goodbye, World!',
                                ),
                            ),
                        ],
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\FunctionCallExpression(
                        location: Location::dummy(),
                        name: 'foo',
                        arguments: [
                            new Literals\StringLiteral(
                                location: Location::dummy(),
                                value: 'Hello, World!',
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
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\IdentifierExpression(
                        location: Location::dummy(),
                        name: 'foo',
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveTupleExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\TupleType(
                        location: Location::dummy(),
                        types: [
                            new Types\PrimitiveType(
                                location: Location::dummy(),
                                type: Primitive::Integer,
                            ),
                            new Types\PrimitiveType(
                                location: Location::dummy(),
                                type: Primitive::Integer,
                            ),
                        ],
                    ),
                    expression: new Expressions\TupleExpression(
                        location: Location::dummy(),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: Location::dummy(),
                                value: 1,
                            ),
                            new Literals\IntegerLiteral(
                                location: Location::dummy(),
                                value: 2,
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveUnaryExpression(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\UnaryExpression(
                        location: Location::dummy(),
                        prefix: false,
                        operator: UnaryOperator::Minus,
                        expression: new Expressions\IdentifierExpression(
                            location: Location::dummy(),
                            name: 'foo',
                        ),
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    #[Test]
    public function testResolveVariableStatement(): void
    {
        $node = new Block(
            location: Location::dummy(),
            statements: [
                new Statements\VariableStatement(
                    location: Location::dummy(),
                    name: 'foo',
                    type: new Types\PrimitiveType(
                        location: Location::dummy(),
                        type: Primitive::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: Location::dummy(),
                        value: 1,
                    ),
                ),
                new Statements\ExpressionStatement(
                    location: Location::dummy(),
                    expression: new Expressions\IdentifierExpression(
                        location: Location::dummy(),
                        name: 'foo',
                    ),
                ),
            ],
        );

        $this->assertSnapshot($node);
    }

    private function assertSnapshot(AbstractNode $node): void
    {
        $symbolResolver = new SymbolResolver($node);
        $typeResolver = new TypeResolver($node, $symbolResolver);

        $this->assertMatchesSnapshot($typeResolver->getFunctionReturnTypes());
        $this->assertMatchesSnapshot($typeResolver->getTypes());
    }
}
