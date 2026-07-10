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

        $expression = new Expressions\AssignmentExpression(
            location: $location,
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: $location,
                value: 1,
            ),
        );

        $declaration = new Statements\VarStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $expression,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($expression),
            expected: $declaration,
        );
    }

    #[Test]
    public function testResolveFunctionCallExpression(): void
    {
        $location = self::createLocation();

        $expression = new Expressions\FunctionCallExpression(
            location: $location,
            name: 'foo',
            arguments: [],
        );

        $declaration = new Statements\FunctionStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $expression,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($expression),
            expected: $declaration,
        );
    }

    #[Test]
    public function testResolveIdentifierExpression(): void
    {
        $location = self::createLocation();

        $expression = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $declaration = new Statements\ConstStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: $location,
                    condition: $expression,
                    statements: new Block(
                        location: $location,
                        statements: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($expression),
            expected: $declaration,
        );
    }

    #[Test]
    public function testResolveParameter(): void
    {
        $location = self::createLocation();

        $expression = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $declaration = new Parameter(
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
                    parameters: [$declaration],
                    type: new Types\BuiltinType(
                        location: $location,
                        type: Types\Type::Void,
                    ),
                    statements: new Block(
                        location: $location,
                        statements: [
                            new Statements\WhileStatement(
                                location: $location,
                                condition: $expression,
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
            actual: new SymbolResolver($root)->resolve($expression),
            expected: $declaration,
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
