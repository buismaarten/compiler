<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\SymbolResolver;

use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
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
        $expression = new Expressions\AssignmentExpression(
            location: self::createLocation(),
            name: 'a',
            expression: new Literals\IntegerLiteral(
                location: self::createLocation(),
                value: 1,
            ),
        );

        $declaration = new Statements\VarStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $expression,
                    statements: new Block(
                        location: self::createLocation(),
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
        $expression = new Expressions\FunctionCallExpression(
            location: self::createLocation(),
            name: 'foo',
            arguments: [],
        );

        $declaration = new Statements\FunctionStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $expression,
                    statements: new Block(
                        location: self::createLocation(),
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
        $expression = new Expressions\IdentifierExpression(
            location: self::createLocation(),
            name: 'a',
        );

        $declaration = new Statements\ConstStatement(
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
                $declaration,
                new Statements\WhileStatement(
                    location: self::createLocation(),
                    condition: $expression,
                    statements: new Block(
                        location: self::createLocation(),
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
