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
        $input = new Expressions\AssignmentExpression(
            location: self::createLocation(),
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $output = new Statements\VarStatement(
            location: self::createLocation(),
            name: 'a',
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $input,
                    statements: new Block(
                        location: self::createLocation(),
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
        $input = new Expressions\FunctionCallExpression(
            location: self::createLocation(),
            name: 'foo',
            arguments: [],
        );

        $output = new Statements\FunctionStatement(
            location: self::createLocation(),
            name: 'foo',
            parameters: [],
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Void,
            ),
            statements: new Block(
                location: self::createLocation(),
                statements: [],
            ),
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $input,
                    statements: new Block(
                        location: self::createLocation(),
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
        $input = new Expressions\IdentifierExpression(
            location: self::createLocation(),
            name: 'a',
        );

        $output = new Parameter(
            location: self::createLocation(),
            name: 'a',
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Integer,
            ),
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                new Statements\FunctionStatement(
                    location: self::createLocation(),
                    name: 'foo',
                    parameters: [$output],
                    type: new Types\BuiltinType(
                        location: self::createLocation(),
                        type: Types\Type::Void,
                    ),
                    statements: new Block(
                        location: self::createLocation(),
                        statements: [
                            new Statements\WhileStatement(
                                location: self::createLocation(),
                                condition: $input,
                                statements: new Block(
                                    location: self::createLocation(),
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
        $input = new Expressions\IdentifierExpression(
            location: self::createLocation(),
            name: 'a',
        );

        $output = new Statements\ConstStatement(
            location: self::createLocation(),
            name: 'a',
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $input,
                    statements: new Block(
                        location: self::createLocation(),
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
        $input = new Types\NamedType(
            location: self::createLocation(),
            name: 'xyz',
        );

        $output = new Statements\ConstStatement(
            location: self::createLocation(),
            name: 'xyz',
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                $output,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: new Literals\BooleanLiteral(
                        location: self::createLocation(),
                        value: true,
                    ),
                    statements: new Block(
                        location: self::createLocation(),
                        statements: [
                            new Statements\FunctionStatement(
                                location: self::createLocation(),
                                name: 'foo',
                                parameters: [],
                                type: $input,
                                statements: new Block(
                                    location: self::createLocation(),
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
