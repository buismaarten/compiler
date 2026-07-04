<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Symbol;

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
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $identifier = new Expressions\IdentifierExpression(
            location: $location,
            name: 'a',
        );

        $statements = [
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
            new Statements\IfStatement(
                location: $location,
                condition: new Literals\BooleanLiteral(
                    location: $location,
                    value: true,
                ),
                thenStatements: [
                    new Statements\IfStatement(
                        location: $location,
                        condition: new Literals\BooleanLiteral(
                            location: $location,
                            value: true,
                        ),
                        thenStatements: [
                            new Statements\PrintStatement(
                                location: $location,
                                expression: $identifier,
                            ),
                        ],
                    ),
                ],
            ),
        ];

        $this->assertEquals(
            actual: new SymbolResolver($statements)->resolve($identifier),
            expected: $statements[0],
        );
    }
}
