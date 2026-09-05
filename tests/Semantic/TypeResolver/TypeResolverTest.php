<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver;

use Package\Core\Location;
use Package\Language\BuiltinTypeKind;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
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
    public function testResolveConstantStatement(): void
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
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::String,
                    ),
                    parameters: [],
                    statements: new Block(
                        location: Location::dummy(),
                        statements: [
                            new Statements\ReturnStatement(
                                location: Location::dummy(),
                                expression: new Literals\StringLiteral(
                                    location: Location::dummy(),
                                    value: 'Hello, World!',
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
                        arguments: [],
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
                    type: new Types\BuiltinType(
                        location: Location::dummy(),
                        kind: BuiltinTypeKind::Integer,
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
    public function testResolveVariableStatement(): void
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

        $this->assertMatchesSnapshot($typeResolver->getTypes());
        $this->assertMatchesSnapshot($typeResolver->getFunctionReturnTypes());
    }
}
