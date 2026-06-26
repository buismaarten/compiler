<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
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
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 10,
                    ),
                    expression: new Expressions\ArrayExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 9,
                        ),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 1,
                                    endLine: 1,
                                    endOffset: 2,
                                ),
                                value: 1,
                            ),
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 4,
                                    endLine: 1,
                                    endOffset: 5,
                                ),
                                value: 2,
                            ),
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 7,
                                    endLine: 1,
                                    endOffset: 8,
                                ),
                                value: 3,
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('[1, 2, [3, 4]];')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 15,
                    ),
                    expression: new Expressions\ArrayExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 14,
                        ),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 1,
                                    endLine: 1,
                                    endOffset: 2,
                                ),
                                value: 1,
                            ),
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 4,
                                    endLine: 1,
                                    endOffset: 5,
                                ),
                                value: 2,
                            ),
                            new Expressions\ArrayExpression(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 7,
                                    endLine: 1,
                                    endOffset: 13,
                                ),
                                expressions: [
                                    new Literals\IntegerLiteral(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 8,
                                            endLine: 1,
                                            endOffset: 9,
                                        ),
                                        value: 3,
                                    ),
                                    new Literals\IntegerLiteral(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 11,
                                            endLine: 1,
                                            endOffset: 12,
                                        ),
                                        value: 4,
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testArrayType(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo: int[] = [123, 456];')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 28,
                    ),
                    name: 'foo',
                    type: new Types\ArrayType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 12,
                            endLine: 1,
                            endOffset: 14,
                        ),
                        type: new Types\NamedType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 9,
                                endLine: 1,
                                endOffset: 12,
                            ),
                            name: 'int',
                        ),
                    ),
                    expression: new Expressions\ArrayExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 17,
                            endLine: 1,
                            endOffset: 27,
                        ),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 18,
                                    endLine: 1,
                                    endOffset: 21,
                                ),
                                value: 123,
                            ),
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 23,
                                    endLine: 1,
                                    endOffset: 26,
                                ),
                                value: 456,
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: int[][] = [[123, 456]];')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 32,
                    ),
                    name: 'foo',
                    type: new Types\ArrayType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 14,
                            endLine: 1,
                            endOffset: 16,
                        ),
                        type: new Types\ArrayType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 12,
                                endLine: 1,
                                endOffset: 14,
                            ),
                            type: new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 9,
                                    endLine: 1,
                                    endOffset: 12,
                                ),
                                name: 'int',
                            ),
                        ),
                    ),
                    expression: new Expressions\ArrayExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 19,
                            endLine: 1,
                            endOffset: 31,
                        ),
                        expressions: [
                            new Expressions\ArrayExpression(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 20,
                                    endLine: 1,
                                    endOffset: 30,
                                ),
                                expressions: [
                                    new Literals\IntegerLiteral(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 21,
                                            endLine: 1,
                                            endOffset: 24,
                                        ),
                                        value: 123,
                                    ),
                                    new Literals\IntegerLiteral(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 26,
                                            endLine: 1,
                                            endOffset: 29,
                                        ),
                                        value: 456,
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: int[]? = null;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 23,
                    ),
                    name: 'foo',
                    type: new Types\NullableType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 14,
                            endLine: 1,
                            endOffset: 15,
                        ),
                        type: new Types\ArrayType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 12,
                                endLine: 1,
                                endOffset: 14,
                            ),
                            type: new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 9,
                                    endLine: 1,
                                    endOffset: 12,
                                ),
                                name: 'int',
                            ),
                        ),
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 18,
                            endLine: 1,
                            endOffset: 22,
                        ),
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: int[]?[]? = null;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 26,
                    ),
                    name: 'foo',
                    type: new Types\NullableType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 17,
                            endLine: 1,
                            endOffset: 18,
                        ),
                        type: new Types\ArrayType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 15,
                                endLine: 1,
                                endOffset: 17,
                            ),
                            type: new Types\NullableType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 14,
                                    endLine: 1,
                                    endOffset: 15,
                                ),
                                type: new Types\ArrayType(
                                    location: new Location(
                                        startLine: 1,
                                        startOffset: 12,
                                        endLine: 1,
                                        endOffset: 14,
                                    ),
                                    type: new Types\NamedType(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 9,
                                            endLine: 1,
                                            endOffset: 12,
                                        ),
                                        name: 'int',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 21,
                            endLine: 1,
                            endOffset: 25,
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testAssignmentExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo = 123;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 10,
                    ),
                    expression: new Expressions\AssignmentExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 9,
                        ),
                        name: 'foo',
                        expression: new Literals\IntegerLiteral(
                            location: new Location(
                                startLine: 1,
                                startOffset: 6,
                                endLine: 1,
                                endOffset: 9,
                            ),
                            value: 123,
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testBinaryExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('1 + 2 * 3;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 10,
                    ),
                    expression: new Expressions\BinaryExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 2,
                            endLine: 1,
                            endOffset: 9,
                        ),
                        operator: Expressions\BinaryOperator::Plus,
                        left: new Literals\IntegerLiteral(
                            location: new Location(
                                startLine: 1,
                                startOffset: 0,
                                endLine: 1,
                                endOffset: 1,
                            ),
                            value: 1,
                        ),
                        right: new Expressions\BinaryExpression(
                            location: new Location(
                                startLine: 1,
                                startOffset: 6,
                                endLine: 1,
                                endOffset: 9,
                            ),
                            operator: Expressions\BinaryOperator::Multiply,
                            left: new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 4,
                                    endLine: 1,
                                    endOffset: 5,
                                ),
                                value: 2,
                            ),
                            right: new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 8,
                                    endLine: 1,
                                    endOffset: 9,
                                ),
                                value: 3,
                            ),
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testBooleanLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('false;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 6,
                    ),
                    expression: new Literals\BooleanLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 5,
                        ),
                        value: false,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('true;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 5,
                    ),
                    expression: new Literals\BooleanLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 4,
                        ),
                        value: true,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testBreakStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('break;')->parse(),
            expected: [
                new Statements\BreakStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 6,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('break 2;')->parse(),
            expected: [
                new Statements\BreakStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 8,
                    ),
                    level: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 6,
                            endLine: 1,
                            endOffset: 7,
                        ),
                        value: 2,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testConstStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('const foo: int = 123;')->parse(),
            expected: [
                new Statements\ConstStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 21,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 11,
                            endLine: 1,
                            endOffset: 14,
                        ),
                        name: 'int',
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 17,
                            endLine: 1,
                            endOffset: 20,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('const foo: xyz = 123;')->parse(),
            expected: [
                new Statements\ConstStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 21,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 11,
                            endLine: 1,
                            endOffset: 14,
                        ),
                        name: 'xyz',
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 17,
                            endLine: 1,
                            endOffset: 20,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testContinueStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('continue;')->parse(),
            expected: [
                new Statements\ContinueStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 9,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('continue 2;')->parse(),
            expected: [
                new Statements\ContinueStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 11,
                    ),
                    level: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 9,
                            endLine: 1,
                            endOffset: 10,
                        ),
                        value: 2,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testFloatLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('3.14;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 5,
                    ),
                    expression: new Literals\FloatLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 4,
                        ),
                        value: 3.14,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testFunctionCallExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo();')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 6,
                    ),
                    expression: new Expressions\FunctionCallExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 5,
                        ),
                        name: 'foo',
                        arguments: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('foo(123, foo("bar"));')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 21,
                    ),
                    expression: new Expressions\FunctionCallExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 20,
                        ),
                        name: 'foo',
                        arguments: [
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 4,
                                    endLine: 1,
                                    endOffset: 7,
                                ),
                                value: 123,
                            ),
                            new Expressions\FunctionCallExpression(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 9,
                                    endLine: 1,
                                    endOffset: 19,
                                ),
                                name: 'foo',
                                arguments: [
                                    new Literals\StringLiteral(
                                        location: new Location(
                                            startLine: 1,
                                            startOffset: 13,
                                            endLine: 1,
                                            endOffset: 18,
                                        ),
                                        value: 'bar',
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testFunctionStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('fn foo(): void { print "Hello, World!"; }')->parse(),
            expected: [
                new Statements\FunctionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 41,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 10,
                            endLine: 1,
                            endOffset: 14,
                        ),
                        name: 'void',
                    ),
                    parameters: [],
                    statements: [
                        new Statements\PrintStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 17,
                                endLine: 1,
                                endOffset: 39,
                            ),
                            expression: new Literals\StringLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 23,
                                    endLine: 1,
                                    endOffset: 38,
                                ),
                                value: 'Hello, World!',
                            ),
                        ),
                    ],
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('fn foo(bar: int, baz: xyz): xyz { return baz; }')->parse(),
            expected: [
                new Statements\FunctionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 47,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 28,
                            endLine: 1,
                            endOffset: 31,
                        ),
                        name: 'xyz',
                    ),
                    parameters: [
                        new Parameter(
                            location: new Location(
                                startLine: 1,
                                startOffset: 7,
                                endLine: 1,
                                endOffset: 15,
                            ),
                            name: 'bar',
                            type: new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 12,
                                    endLine: 1,
                                    endOffset: 15,
                                ),
                                name: 'int',
                            ),
                        ),
                        new Parameter(
                            location: new Location(
                                startLine: 1,
                                startOffset: 17,
                                endLine: 1,
                                endOffset: 25,
                            ),
                            name: 'baz',
                            type: new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 22,
                                    endLine: 1,
                                    endOffset: 25,
                                ),
                                name: 'xyz',
                            ),
                        ),
                    ],
                    statements: [
                        new Statements\ReturnStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 34,
                                endLine: 1,
                                endOffset: 45,
                            ),
                            expression: new Expressions\IdentifierExpression(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 41,
                                    endLine: 1,
                                    endOffset: 44,
                                ),
                                name: 'baz',
                            ),
                        ),
                    ],
                ),
            ],
        );
    }

    #[Test]
    public function testIdentifierExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 4,
                    ),
                    expression: new Expressions\IdentifierExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 3,
                        ),
                        name: 'foo',
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testIfStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('if (foo) { return 1; }')->parse(),
            expected: [
                new Statements\IfStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 22,
                    ),
                    condition: new Expressions\IdentifierExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 4,
                            endLine: 1,
                            endOffset: 7,
                        ),
                        name: 'foo',
                    ),
                    thenStatements: [
                        new Statements\ReturnStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 11,
                                endLine: 1,
                                endOffset: 20,
                            ),
                            expression: new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 18,
                                    endLine: 1,
                                    endOffset: 19,
                                ),
                                value: 1,
                            ),
                        ),
                    ],
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('if (foo) { return 1; } else { return 2; }')->parse(),
            expected: [
                new Statements\IfStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 41,
                    ),
                    condition: new Expressions\IdentifierExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 4,
                            endLine: 1,
                            endOffset: 7,
                        ),
                        name: 'foo',
                    ),
                    thenStatements: [
                        new Statements\ReturnStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 11,
                                endLine: 1,
                                endOffset: 20,
                            ),
                            expression: new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 18,
                                    endLine: 1,
                                    endOffset: 19,
                                ),
                                value: 1,
                            ),
                        ),
                    ],
                    elseStatements: [
                        new Statements\ReturnStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 30,
                                endLine: 1,
                                endOffset: 39,
                            ),
                            expression: new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 37,
                                    endLine: 1,
                                    endOffset: 38,
                                ),
                                value: 2,
                            ),
                        ),
                    ],
                ),
            ],
        );
    }

    #[Test]
    public function testIntegerLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('123;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 4,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 3,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testNamedType(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo: int = 123;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 19,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 9,
                            endLine: 1,
                            endOffset: 12,
                        ),
                        name: 'int',
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 15,
                            endLine: 1,
                            endOffset: 18,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testNullableType(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo: int? = null;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 21,
                    ),
                    name: 'foo',
                    type: new Types\NullableType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 12,
                            endLine: 1,
                            endOffset: 13,
                        ),
                        type: new Types\NamedType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 9,
                                endLine: 1,
                                endOffset: 12,
                            ),
                            name: 'int',
                        ),
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 16,
                            endLine: 1,
                            endOffset: 20,
                        ),
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: int?[] = null;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 23,
                    ),
                    name: 'foo',
                    type: new Types\ArrayType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 13,
                            endLine: 1,
                            endOffset: 15,
                        ),
                        type: new Types\NullableType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 12,
                                endLine: 1,
                                endOffset: 13,
                            ),
                            type: new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 9,
                                    endLine: 1,
                                    endOffset: 12,
                                ),
                                name: 'int',
                            ),
                        ),
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 18,
                            endLine: 1,
                            endOffset: 22,
                        ),
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: int?[]? = null;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 24,
                    ),
                    name: 'foo',
                    type: new Types\NullableType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 15,
                            endLine: 1,
                            endOffset: 16,
                        ),
                        type: new Types\ArrayType(
                            location: new Location(
                                startLine: 1,
                                startOffset: 13,
                                endLine: 1,
                                endOffset: 15,
                            ),
                            type: new Types\NullableType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 12,
                                    endLine: 1,
                                    endOffset: 13,
                                ),
                                type: new Types\NamedType(
                                    location: new Location(
                                        startLine: 1,
                                        startOffset: 9,
                                        endLine: 1,
                                        endOffset: 12,
                                    ),
                                    name: 'int',
                                ),
                            ),
                        ),
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 19,
                            endLine: 1,
                            endOffset: 23,
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testNullLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('null;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 5,
                    ),
                    expression: new Literals\NullLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 4,
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testPostfixUnaryExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo--;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 6,
                    ),
                    expression: new Expressions\UnaryExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 5,
                        ),
                        operator: Expressions\UnaryOperator::Decrement,
                        position: Expressions\UnaryPosition::Postfix,
                        expression: new Expressions\IdentifierExpression(
                            location: new Location(
                                startLine: 1,
                                startOffset: 0,
                                endLine: 1,
                                endOffset: 3,
                            ),
                            name: 'foo',
                        ),
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('foo++;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 6,
                    ),
                    expression: new Expressions\UnaryExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 5,
                        ),
                        operator: Expressions\UnaryOperator::Increment,
                        position: Expressions\UnaryPosition::Postfix,
                        expression: new Expressions\IdentifierExpression(
                            location: new Location(
                                startLine: 1,
                                startOffset: 0,
                                endLine: 1,
                                endOffset: 3,
                            ),
                            name: 'foo',
                        ),
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testPrefixUnaryExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('~foo;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 5,
                    ),
                    expression: new Expressions\UnaryExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 4,
                        ),
                        operator: Expressions\UnaryOperator::BitwiseNot,
                        position: Expressions\UnaryPosition::Prefix,
                        expression: new Expressions\IdentifierExpression(
                            location: new Location(
                                startLine: 1,
                                startOffset: 1,
                                endLine: 1,
                                endOffset: 4,
                            ),
                            name: 'foo',
                        ),
                    ),
                ),
            ],
        );

        // $this->assertEquals(
        //     actual: self::createParser('!foo;')->parse(),
        //     expected: [
        //         new Statements\ExpressionStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             expression: new Expressions\UnaryExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 operator: Expressions\UnaryOperator::BooleanNot,
        //                 position: Expressions\UnaryPosition::Prefix,
        //                 expression: new Expressions\IdentifierExpression(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     name: 'foo',
        //                 ),
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('--foo;')->parse(),
        //     expected: [
        //         new Statements\ExpressionStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             expression: new Expressions\UnaryExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 operator: Expressions\UnaryOperator::Decrement,
        //                 position: Expressions\UnaryPosition::Prefix,
        //                 expression: new Expressions\IdentifierExpression(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     name: 'foo',
        //                 ),
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('++foo;')->parse(),
        //     expected: [
        //         new Statements\ExpressionStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             expression: new Expressions\UnaryExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 operator: Expressions\UnaryOperator::Increment,
        //                 position: Expressions\UnaryPosition::Prefix,
        //                 expression: new Expressions\IdentifierExpression(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     name: 'foo',
        //                 ),
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('-foo;')->parse(),
        //     expected: [
        //         new Statements\ExpressionStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             expression: new Expressions\UnaryExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 operator: Expressions\UnaryOperator::Minus,
        //                 position: Expressions\UnaryPosition::Prefix,
        //                 expression: new Expressions\IdentifierExpression(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     name: 'foo',
        //                 ),
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('+foo;')->parse(),
        //     expected: [
        //         new Statements\ExpressionStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             expression: new Expressions\UnaryExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 operator: Expressions\UnaryOperator::Plus,
        //                 position: Expressions\UnaryPosition::Prefix,
        //                 expression: new Expressions\IdentifierExpression(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     name: 'foo',
        //                 ),
        //             ),
        //         ),
        //     ],
        // );
    }

    #[Test]
    public function testPrintStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('print 123;')->parse(),
            expected: [
                new Statements\PrintStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 10,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 6,
                            endLine: 1,
                            endOffset: 9,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testReturnStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('return;')->parse(),
            expected: [
                new Statements\ReturnStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 7,
                    ),
                    expression: null,
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('return 123;')->parse(),
            expected: [
                new Statements\ReturnStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 11,
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 7,
                            endLine: 1,
                            endOffset: 10,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testStringLiteral(): void
    {
        $this->assertEquals(
            actual: self::createParser('"Hello, World!";')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 16,
                    ),
                    expression: new Literals\StringLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 0,
                            endLine: 1,
                            endOffset: 15,
                        ),
                        value: 'Hello, World!',
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testTupleType(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo: (int, int) = (123, 456);')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 33,
                    ),
                    name: 'foo',
                    type: new Types\TupleType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 9,
                            endLine: 1,
                            endOffset: 19,
                        ),
                        types: [
                            new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 10,
                                    endLine: 1,
                                    endOffset: 13,
                                ),
                                name: 'int',
                            ),
                            new Types\NamedType(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 15,
                                    endLine: 1,
                                    endOffset: 18,
                                ),
                                name: 'int',
                            ),
                        ],
                    ),
                    expression: new Expressions\TupleExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 22,
                            endLine: 1,
                            endOffset: 32,
                        ),
                        expressions: [
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 23,
                                    endLine: 1,
                                    endOffset: 26,
                                ),
                                value: 123,
                            ),
                            new Literals\IntegerLiteral(
                                location: new Location(
                                    startLine: 1,
                                    startOffset: 28,
                                    endLine: 1,
                                    endOffset: 31,
                                ),
                                value: 456,
                            ),
                        ],
                    ),
                ),
            ],
        );

        // $this->assertEquals(
        //     actual: self::createParser('var foo: (int, int) = ((123, 456));')->parse(),
        //     expected: [
        //         new Statements\VarStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             name: 'foo',
        //             type: new Types\TupleType(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 types: [
        //                     new Types\NamedType(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         name: 'int',
        //                     ),
        //                     new Types\NamedType(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         name: 'int',
        //                     ),
        //                 ],
        //             ),
        //             expression: new Expressions\TupleExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 expressions: [
        //                     new Literals\IntegerLiteral(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         value: 123,
        //                     ),
        //                     new Literals\IntegerLiteral(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         value: 456,
        //                     ),
        //                 ],
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('var foo: (int, int)[] = [(123, 456)];')->parse(),
        //     expected: [
        //         new Statements\VarStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             name: 'foo',
        //             type: new Types\ArrayType(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 type: new Types\TupleType(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     types: [
        //                         new Types\NamedType(
        //                             location: new Location(
        //                                 startLine: 1,
        //                                 endLine: 1,
        //                             ),
        //                             name: 'int',
        //                         ),
        //                         new Types\NamedType(
        //                             location: new Location(
        //                                 startLine: 1,
        //                                 endLine: 1,
        //                             ),
        //                             name: 'int',
        //                         ),
        //                     ],
        //                 ),
        //             ),
        //             expression: new Expressions\ArrayExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 expressions: [
        //                     new Expressions\TupleExpression(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         expressions: [
        //                             new Literals\IntegerLiteral(
        //                                 location: new Location(
        //                                     startLine: 1,
        //                                     endLine: 1,
        //                                 ),
        //                                 value: 123,
        //                             ),
        //                             new Literals\IntegerLiteral(
        //                                 location: new Location(
        //                                     startLine: 1,
        //                                     endLine: 1,
        //                                 ),
        //                                 value: 456,
        //                             ),
        //                         ],
        //                     ),
        //                 ],
        //             ),
        //         ),
        //     ],
        // );

        // $this->assertEquals(
        //     actual: self::createParser('var foo: (int, int)? = (123, 456);')->parse(),
        //     expected: [
        //         new Statements\VarStatement(
        //             location: new Location(
        //                 startLine: 1,
        //                 endLine: 1,
        //             ),
        //             name: 'foo',
        //             type: new Types\NullableType(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 type: new Types\TupleType(
        //                     location: new Location(
        //                         startLine: 1,
        //                         endLine: 1,
        //                     ),
        //                     types: [
        //                         new Types\NamedType(
        //                             location: new Location(
        //                                 startLine: 1,
        //                                 endLine: 1,
        //                             ),
        //                             name: 'int',
        //                         ),
        //                         new Types\NamedType(
        //                             location: new Location(
        //                                 startLine: 1,
        //                                 endLine: 1,
        //                             ),
        //                             name: 'int',
        //                         ),
        //                     ],
        //                 ),
        //             ),
        //             expression: new Expressions\TupleExpression(
        //                 location: new Location(
        //                     startLine: 1,
        //                     endLine: 1,
        //                 ),
        //                 expressions: [
        //                     new Literals\IntegerLiteral(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         value: 123,
        //                     ),
        //                     new Literals\IntegerLiteral(
        //                         location: new Location(
        //                             startLine: 1,
        //                             endLine: 1,
        //                         ),
        //                         value: 456,
        //                     ),
        //                 ],
        //             ),
        //         ),
        //     ],
        // );
    }

    #[Test]
    public function testVarStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo: int = 123;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 19,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 9,
                            endLine: 1,
                            endOffset: 12,
                        ),
                        name: 'int',
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 15,
                            endLine: 1,
                            endOffset: 18,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('var foo: xyz = 123;')->parse(),
            expected: [
                new Statements\VarStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 19,
                    ),
                    name: 'foo',
                    type: new Types\NamedType(
                        location: new Location(
                            startLine: 1,
                            startOffset: 9,
                            endLine: 1,
                            endOffset: 12,
                        ),
                        name: 'xyz',
                    ),
                    expression: new Literals\IntegerLiteral(
                        location: new Location(
                            startLine: 1,
                            startOffset: 15,
                            endLine: 1,
                            endOffset: 18,
                        ),
                        value: 123,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testWhileStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('while (foo) { break; }')->parse(),
            expected: [
                new Statements\WhileStatement(
                    location: new Location(
                        startLine: 1,
                        startOffset: 0,
                        endLine: 1,
                        endOffset: 22,
                    ),
                    condition: new Expressions\IdentifierExpression(
                        location: new Location(
                            startLine: 1,
                            startOffset: 7,
                            endLine: 1,
                            endOffset: 10,
                        ),
                        name: 'foo',
                    ),
                    statements: [
                        new Statements\BreakStatement(
                            location: new Location(
                                startLine: 1,
                                startOffset: 14,
                                endLine: 1,
                                endOffset: 20,
                            ),
                        ),
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
