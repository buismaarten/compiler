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
            location: new Location(
                startLine: -1,
                startOffset: -1,
                endLine: -1,
                endOffset: -1,
            ),
            name: 'a',
            type: new Types\BuiltinType(
                location: new Location(
                    startLine: -1,
                    startOffset: -1,
                    endLine: -1,
                    endOffset: -1,
                ),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: new Location(
                    startLine: -1,
                    startOffset: -1,
                    endLine: -1,
                    endOffset: -1,
                ),
                value: 1,
            ),
        );

        $variable2 = new Statements\VarStatement(
            location: new Location(
                startLine: -1,
                startOffset: -1,
                endLine: -1,
                endOffset: -1,
            ),
            name: 'b',
            type: new Types\BuiltinType(
                location: new Location(
                    startLine: -1,
                    startOffset: -1,
                    endLine: -1,
                    endOffset: -1,
                ),
                type: Types\Type::Integer,
            ),
            expression: new Literals\IntegerLiteral(
                location: new Location(
                    startLine: -1,
                    startOffset: -1,
                    endLine: -1,
                    endOffset: -1,
                ),
                value: 1,
            ),
        );

        $identifier1 = new Expressions\IdentifierExpression(
            location: new Location(
                startLine: -1,
                startOffset: -1,
                endLine: -1,
                endOffset: -1,
            ),
            name: 'a',
        );

        $identifier2 = new Expressions\IdentifierExpression(
            location: new Location(
                startLine: -1,
                startOffset: -1,
                endLine: -1,
                endOffset: -1,
            ),
            name: 'b',
        );

        $root = new Block(
            location: new Location(
                startLine: -1,
                startOffset: -1,
                endLine: -1,
                endOffset: -1,
            ),
            statements: [
                $variable1,
                new Statements\VarStatement(
                    location: new Location(
                        startLine: -1,
                        startOffset: -1,
                        endLine: -1,
                        endOffset: -1,
                    ),
                    name: 'a',
                    type: new Types\BuiltinType(
                        location: new Location(
                            startLine: -1,
                            startOffset: -1,
                            endLine: -1,
                            endOffset: -1,
                        ),
                        type: Types\Type::Integer,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: -1,
                            startOffset: -1,
                            endLine: -1,
                            endOffset: -1,
                        ),
                        value: 1,
                    ),
                ),
                new Statements\IfStatement(
                    location: new Location(
                        startLine: -1,
                        startOffset: -1,
                        endLine: -1,
                        endOffset: -1,
                    ),
                    condition: new Literals\BooleanLiteral(
                        location: new Location(
                            startLine: -1,
                            startOffset: -1,
                            endLine: -1,
                            endOffset: -1,
                        ),
                        value: true,
                    ),
                    thenStatements: new Block(
                        location: new Location(
                            startLine: -1,
                            startOffset: -1,
                            endLine: -1,
                            endOffset: -1,
                        ),
                        statements: [
                            $variable2,
                            new Statements\IfStatement(
                                location: new Location(
                                    startLine: -1,
                                    startOffset: -1,
                                    endLine: -1,
                                    endOffset: -1,
                                ),
                                condition: new Literals\BooleanLiteral(
                                    location: new Location(
                                        startLine: -1,
                                        startOffset: -1,
                                        endLine: -1,
                                        endOffset: -1,
                                    ),
                                    value: false,
                                ),
                                thenStatements: new Block(
                                    location: new Location(
                                        startLine: -1,
                                        startOffset: -1,
                                        endLine: -1,
                                        endOffset: -1,
                                    ),
                                    statements: [
                                        new Statements\PrintStatement(
                                            location: new Location(
                                                startLine: -1,
                                                startOffset: -1,
                                                endLine: -1,
                                                endOffset: -1,
                                            ),
                                            expression: $identifier1,
                                        ),
                                        new Statements\PrintStatement(
                                            location: new Location(
                                                startLine: -1,
                                                startOffset: -1,
                                                endLine: -1,
                                                endOffset: -1,
                                            ),
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
}
