<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\Parser\Parser;
use Package\Parser\ParserInterface;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    #[Test]
    public function testArrayExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('[1, 2, 3];')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    expression: new Expressions\ArrayExpression(
                        expressions: [
                            new Literals\IntegerLiteral(
                                value: 1,
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                            new Literals\IntegerLiteral(
                                value: 2,
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                            new Literals\IntegerLiteral(
                                value: 3,
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                        ],
                        attributes: [
                            'lineStart' => 1,
                            'lineEnd' => 1,
                        ],
                    ),
                    attributes: [
                        'lineStart' => 1,
                        'lineEnd' => 1,
                    ],
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('[1, 2, [3, 4]];')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    expression: new Expressions\ArrayExpression(
                        expressions: [
                            new Literals\IntegerLiteral(
                                value: 1,
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                            new Literals\IntegerLiteral(
                                value: 2,
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                            new Expressions\ArrayExpression(
                                expressions: [
                                    new Literals\IntegerLiteral(
                                        value: 3,
                                        attributes: [
                                            'lineStart' => 1,
                                            'lineEnd' => 1,
                                        ],
                                    ),
                                    new Literals\IntegerLiteral(
                                        value: 4,
                                        attributes: [
                                            'lineStart' => 1,
                                            'lineEnd' => 1,
                                        ],
                                    ),
                                ],
                                attributes: [
                                    'lineStart' => 1,
                                    'lineEnd' => 1,
                                ],
                            ),
                        ],
                        attributes: [
                            'lineStart' => 1,
                            'lineEnd' => 1,
                        ],
                    ),
                    attributes: [
                        'lineStart' => 1,
                        'lineEnd' => 1,
                    ],
                ),
            ],
        );
    }

    // #[Test]
    // public function testArrayType(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int[] = [123, 456];')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\ArrayType(
    //                     line: 1,
    //                     type: new Types\NamedType(
    //                         line: 1,
    //                         name: 'int',
    //                     ),
    //                 ),
    //                 expression: new Expressions\ArrayExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 123,
    //                         ),
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 456,
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int[][] = [[123, 456]];')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\ArrayType(
    //                     line: 1,
    //                     type: new Types\ArrayType(
    //                         line: 1,
    //                         type: new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ),
    //                 ),
    //                 expression: new Expressions\ArrayExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Expressions\ArrayExpression(
    //                             line: 1,
    //                             expressions: [
    //                                 new Literals\IntegerLiteral(
    //                                     line: 1,
    //                                     value: 123,
    //                                 ),
    //                                 new Literals\IntegerLiteral(
    //                                     line: 1,
    //                                     value: 456,
    //                                 ),
    //                             ],
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int[]? = null;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NullableType(
    //                     line: 1,
    //                     type: new Types\ArrayType(
    //                         line: 1,
    //                         type: new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ),
    //                 ),
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int[]?[]? = null;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NullableType(
    //                     line: 1,
    //                     type: new Types\ArrayType(
    //                         line: 1,
    //                         type: new Types\NullableType(
    //                             line: 1,
    //                             type: new Types\ArrayType(
    //                                 line: 1,
    //                                 type: new Types\NamedType(
    //                                     line: 1,
    //                                     name: 'int',
    //                                 ),
    //                             ),
    //                         ),
    //                     ),
    //                 ),
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testAssignmentExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('foo = 123;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\AssignmentExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                     expression: new Literals\IntegerLiteral(
    //                         line: 1,
    //                         value: 123,
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testBinaryExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('1 + 2 * 3;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\BinaryExpression(
    //                     line: 1,
    //                     operator: Expressions\BinaryOperator::Plus,
    //                     left: new Literals\IntegerLiteral(
    //                         line: 1,
    //                         value: 1,
    //                     ),
    //                     right: new Expressions\BinaryExpression(
    //                         line: 1,
    //                         operator: Expressions\BinaryOperator::Multiply,
    //                         left: new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 2,
    //                         ),
    //                         right: new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 3,
    //                         ),
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testBooleanLiteral(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('false;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Literals\BooleanLiteral(
    //                     line: 1,
    //                     value: false,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('true;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Literals\BooleanLiteral(
    //                     line: 1,
    //                     value: true,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testBreakStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('break;')->parse(),
    //         expected: [
    //             new Statements\BreakStatement(
    //                 line: 1,
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('break 2;')->parse(),
    //         expected: [
    //             new Statements\BreakStatement(
    //                 line: 1,
    //                 level: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 2,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testConstStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('const foo: int = 123;')->parse(),
    //         expected: [
    //             new Statements\ConstStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'int',
    //                 ),
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('const foo: xyz = 123;')->parse(),
    //         expected: [
    //             new Statements\ConstStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'xyz',
    //                 ),
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testContinueStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('continue;')->parse(),
    //         expected: [
    //             new Statements\ContinueStatement(
    //                 line: 1,
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('continue 2;')->parse(),
    //         expected: [
    //             new Statements\ContinueStatement(
    //                 line: 1,
    //                 level: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 2,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testFloatLiteral(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('3.14;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Literals\FloatLiteral(
    //                     line: 1,
    //                     value: 3.14,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testFunctionCallExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('foo();')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\FunctionCallExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                     arguments: [],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('foo(123, foo("bar"));')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\FunctionCallExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                     arguments: [
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 123,
    //                         ),
    //                         new Expressions\FunctionCallExpression(
    //                             line: 1,
    //                             name: 'foo',
    //                             arguments: [
    //                                 new Literals\StringLiteral(
    //                                     line: 1,
    //                                     value: 'bar',
    //                                 ),
    //                             ],
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testFunctionStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('fn foo(): void { print "Hello, World!"; }')->parse(),
    //         expected: [
    //             new Statements\FunctionStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'void',
    //                 ),
    //                 parameters: [],
    //                 statements: [
    //                     new Statements\PrintStatement(
    //                         line: 1,
    //                         expression: new Literals\StringLiteral(
    //                             line: 1,
    //                             value: 'Hello, World!',
    //                         ),
    //                     ),
    //                 ],
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('fn foo(bar: int, baz: xyz): xyz { return baz; }')->parse(),
    //         expected: [
    //             new Statements\FunctionStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'xyz',
    //                 ),
    //                 parameters: [
    //                     new Parameter(
    //                         line: 1,
    //                         name: 'bar',
    //                         type: new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ),
    //                     new Parameter(
    //                         line: 1,
    //                         name: 'baz',
    //                         type: new Types\NamedType(
    //                             line: 1,
    //                             name: 'xyz',
    //                         ),
    //                     ),
    //                 ],
    //                 statements: [
    //                     new Statements\ReturnStatement(
    //                         line: 1,
    //                         expression: new Expressions\IdentifierExpression(
    //                             line: 1,
    //                             name: 'baz',
    //                         ),
    //                     ),
    //                 ],
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testIdentifierExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\IdentifierExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testIfStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('if (foo) { break; }')->parse(),
    //         expected: [
    //             new Statements\IfStatement(
    //                 line: 1,
    //                 condition: new Expressions\IdentifierExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                 ),
    //                 thenStatements: [
    //                     new Statements\BreakStatement(
    //                         line: 1,
    //                     ),
    //                 ],
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('if (foo) { break; } else { continue; }')->parse(),
    //         expected: [
    //             new Statements\IfStatement(
    //                 line: 1,
    //                 condition: new Expressions\IdentifierExpression(
    //                     line: 1,
    //                     name: 'foo',
    //                 ),
    //                 thenStatements: [
    //                     new Statements\BreakStatement(
    //                         line: 1,
    //                     ),
    //                 ],
    //                 elseStatements: [
    //                     new Statements\ContinueStatement(
    //                         line: 1,
    //                     ),
    //                 ],
    //             ),
    //         ],
    //     );
    // }

    #[Test]
    public function testIntegerLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('123;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    expression: new Literals\IntegerLiteral(
                        value: 123,
                        attributes: [
                            'lineStart' => 1,
                            'lineEnd' => 1,
                        ],
                    ),
                    attributes: [
                        'lineStart' => 1,
                        'lineEnd' => 1,
                    ],
                ),
            ],
        );
    }

    // #[Test]
    // public function testNamedType(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int = 123;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'int',
    //                 ),
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testNullableType(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int? = null;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NullableType(
    //                     line: 1,
    //                     type: new Types\NamedType(
    //                         line: 1,
    //                         name: 'int',
    //                     ),
    //                 ),
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int?[] = null;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\ArrayType(
    //                     line: 1,
    //                     type: new Types\NullableType(
    //                         line: 1,
    //                         type: new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ),
    //                 ),
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int?[]? = null;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NullableType(
    //                     line: 1,
    //                     type: new Types\ArrayType(
    //                         line: 1,
    //                         type: new Types\NullableType(
    //                             line: 1,
    //                             type: new Types\NamedType(
    //                                 line: 1,
    //                                 name: 'int',
    //                             ),
    //                         ),
    //                     ),
    //                 ),
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testNullLiteral(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('null;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Literals\NullLiteral(
    //                     line: 1,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testPostfixUnaryExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('foo--;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Decrement,
    //                     position: Expressions\UnaryPosition::Postfix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('foo++;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Increment,
    //                     position: Expressions\UnaryPosition::Postfix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testPrefixUnaryExpression(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('~foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::BitwiseNot,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('!foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::BooleanNot,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('--foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Decrement,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('++foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Increment,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('-foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Minus,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('+foo;')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Expressions\UnaryExpression(
    //                     line: 1,
    //                     operator: Expressions\UnaryOperator::Plus,
    //                     position: Expressions\UnaryPosition::Prefix,
    //                     expression: new Expressions\IdentifierExpression(
    //                         line: 1,
    //                         name: 'foo',
    //                     ),
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    #[Test]
    public function testPrintStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('print 123;')->parse(),
            expected: [
                new Statements\PrintStatement(
                    expression: new Literals\IntegerLiteral(
                        value: 123,
                        attributes: [
                            'lineStart' => 1,
                            'lineEnd' => 1,
                        ],
                    ),
                    attributes: [
                        'lineStart' => 1,
                        'lineEnd' => 1,
                    ],
                ),
            ],
        );
    }

    // #[Test]
    // public function testReturnStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('return;')->parse(),
    //         expected: [
    //             new Statements\ReturnStatement(
    //                 line: 1,
    //                 expression: null,
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('return 123;')->parse(),
    //         expected: [
    //             new Statements\ReturnStatement(
    //                 line: 1,
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testStringLiteral(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('"Hello, World!";')->parse(),
    //         expected: [
    //             new Statements\ExpressionStatement(
    //                 line: 1,
    //                 expression: new Literals\StringLiteral(
    //                     line: 1,
    //                     value: 'Hello, World!',
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testTupleType(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('var foo: (int, int) = (123, 456);')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\TupleType(
    //                     line: 1,
    //                     types: [
    //                         new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                         new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ],
    //                 ),
    //                 expression: new Expressions\TupleExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 123,
    //                         ),
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 456,
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: (int, int) = ((123, 456));')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\TupleType(
    //                     line: 1,
    //                     types: [
    //                         new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                         new Types\NamedType(
    //                             line: 1,
    //                             name: 'int',
    //                         ),
    //                     ],
    //                 ),
    //                 expression: new Expressions\TupleExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 123,
    //                         ),
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 456,
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: (int, int)[] = [(123, 456)];')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\ArrayType(
    //                     line: 1,
    //                     type: new Types\TupleType(
    //                         line: 1,
    //                         types: [
    //                             new Types\NamedType(
    //                                 line: 1,
    //                                 name: 'int',
    //                             ),
    //                             new Types\NamedType(
    //                                 line: 1,
    //                                 name: 'int',
    //                             ),
    //                         ],
    //                     ),
    //                 ),
    //                 expression: new Expressions\ArrayExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Expressions\TupleExpression(
    //                             line: 1,
    //                             expressions: [
    //                                 new Literals\IntegerLiteral(
    //                                     line: 1,
    //                                     value: 123,
    //                                 ),
    //                                 new Literals\IntegerLiteral(
    //                                     line: 1,
    //                                     value: 456,
    //                                 ),
    //                             ],
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: (int, int)? = (123, 456);')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NullableType(
    //                     line: 1,
    //                     type: new Types\TupleType(
    //                         line: 1,
    //                         types: [
    //                             new Types\NamedType(
    //                                 line: 1,
    //                                 name: 'int',
    //                             ),
    //                             new Types\NamedType(
    //                                 line: 1,
    //                                 name: 'int',
    //                             ),
    //                         ],
    //                     ),
    //                 ),
    //                 expression: new Expressions\TupleExpression(
    //                     line: 1,
    //                     expressions: [
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 123,
    //                         ),
    //                         new Literals\IntegerLiteral(
    //                             line: 1,
    //                             value: 456,
    //                         ),
    //                     ],
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    // #[Test]
    // public function testVarStatement(): void
    // {
    //     $this->assertEquals(
    //         actual: self::createParser('var foo: int = 123;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'int',
    //                 ),
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );

    //     $this->assertEquals(
    //         actual: self::createParser('var foo: xyz = 123;')->parse(),
    //         expected: [
    //             new Statements\VarStatement(
    //                 line: 1,
    //                 name: 'foo',
    //                 type: new Types\NamedType(
    //                     line: 1,
    //                     name: 'xyz',
    //                 ),
    //                 expression: new Literals\IntegerLiteral(
    //                     line: 1,
    //                     value: 123,
    //                 ),
    //             ),
    //         ],
    //     );
    // }

    #[Test]
    public function testWhileStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('while (foo) { break; }')->parse(),
            expected: [
                new Statements\WhileStatement(
                    condition: new Expressions\IdentifierExpression(
                        name: 'foo',
                        attributes: [
                            'lineStart' => 1,
                            'lineEnd' => 1,
                        ],
                    ),
                    statements: [
                        new Statements\BreakStatement(
                            attributes: [
                                'lineStart' => 1,
                                'lineEnd' => 1,
                            ],
                        ),
                    ],
                    attributes: [
                        'lineStart' => 1,
                        'lineEnd' => 1,
                    ],
                ),
            ],
        );
    }

    private static function createParser(string $input): ParserInterface
    {
        $tokenizer = new Tokenizer($input);
        $parser = new Parser($tokenizer);

        return $parser;
    }
}
