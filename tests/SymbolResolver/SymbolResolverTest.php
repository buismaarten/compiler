<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\SymbolResolver;

use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\SymbolResolver\SymbolResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SymbolResolverTest extends TestCase
{
    #[Test]
    public function testResolveAssignmentExpression(): void
    {
        $location = self::createLocation();

        $input = new Expressions\AssignmentExpression(
            location: $location,
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: $location,
                value: 1,
            ),
        );

        $output = new Statements\VarStatement(
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
        );

        $root = new Block(
            location: $location,
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $input,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($input),
            expected: $output,
        );
    }

    #[Test]
    public function testResolveFunctionCallExpression(): void
    {
        $location = self::createLocation();

        $input = new Expressions\FunctionCallExpression(
            location: $location,
            name: 'foo',
            arguments: [],
        );

        $output = new Statements\FunctionStatement(
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
        );

        $root = new Block(
            location: $location,
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $input,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($input),
            expected: $output,
        );
    }

    #[Test]
    public function testResolveFunctionParameter(): void
    {
        $location = self::createLocation();

        $input = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $output = new Parameter(
            location: $location,
            name: 'a',
            type: new Types\BuiltinType(
                location: $location,
                type: Types\Type::Integer,
            ),
        );

        $root = new Block(
            location: $location,
            statements: [
                new Statements\FunctionStatement(
                    location: $location,
                    name: 'foo',
                    parameters: [$output],
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Void,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\WhileStatement(
                                location: $location,
                                condition: $input,
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

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($input),
            expected: $output,
        );
    }

    #[Test]
    public function testResolveIdentifierExpression(): void
    {
        $location = self::createLocation();

        $input = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $output = new Statements\ConstStatement(
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
        );

        $root = new Block(
            location: $location,
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $input,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($input),
            expected: $output,
        );
    }

    #[Test]
    public function testResolveNamedType(): void
    {
        $location = self::createLocation();

        $input = new Types\NamedType(
            location: $location,
            name: 'xyz',
        );

        $output = new Statements\ConstStatement(
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
        );

        $root = new Block(
            location: $location,
            statements: [
                $output,
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
                                type: $input,
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

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($input),
            expected: $output,
        );
    }

    private static function createLocation(): Location
    {
        return new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );
    }
}
