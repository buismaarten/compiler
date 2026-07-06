<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Symbol;

use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\Symbol\SymbolResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SymbolResolverTest extends TestCase
{
    #[Test]
    public function testResolve(): void
    {
        $variable1 = new Statements\VarStatement(
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

        $variable2 = new Statements\VarStatement(
            location: self::createLocation(),
            name: 'b',
            type: new Types\BuiltinType(
                location: self::createLocation(),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $identifier1 = new Expressions\IdentifierExpression(
            location: self::createLocation(),
            name: 'a',
        );

        $identifier2 = new Expressions\IdentifierExpression(
            location: self::createLocation(),
            name: 'b',
        );

        $root = new Block(
            location: self::createLocation(),
            statements: [
                $variable1,
                new Statements\VarStatement(
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
                ),
                new Statements\IfStatement(
                    location: self::createLocation(),
                    condition: new Literals\BooleanLiteral(
                        location: self::createLocation(),
                        value: true,
                    ),
                    thenStatements: new Block(
                        location: self::createLocation(),
                        statements: [
                            $variable2,
                            new Statements\IfStatement(
                                location: self::createLocation(),
                                condition: new Literals\BooleanLiteral(
                                    location: self::createLocation(),
                                    value: false,
                                ),
                                thenStatements: new Block(
                                    location: self::createLocation(),
                                    statements: [
                                        new Statements\PrintStatement(
                                            location: self::createLocation(),
                                            expression: $identifier1,
                                        ),
                                        new Statements\PrintStatement(
                                            location: self::createLocation(),
                                            expression: $identifier2,
                                        ),
                                    ],
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($identifier1),
            expected: $variable1,
        );

        $this->assertEquals(
            actual: new SymbolResolver($root)->resolve($identifier2),
            expected: $variable2,
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
