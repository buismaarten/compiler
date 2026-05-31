<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Parser\Parser;
use Package\Parser\ParserInterface;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    #[Test]
    public function testAssignmentExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo = 123;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\AssignmentExpression(
                        line: 1,
                        name: 'foo',
                        expression: new Literals\IntegerLiteral(
                            line: 1,
                            value: 123,
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
                    line: 1,
                    expression: new Literals\BooleanLiteral(
                        line: 1,
                        value: false,
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('true;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Literals\BooleanLiteral(
                        line: 1,
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
                    line: 1,
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('break 2;')->parse(),
            expected: [
                new Statements\BreakStatement(
                    line: 1,
                    level: new Literals\IntegerLiteral(
                        line: 1,
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
            actual: self::createParser('const foo = 123;')->parse(),
            expected: [
                new Statements\ConstStatement(
                    line: 1,
                    name: 'foo',
                    expression: new Literals\IntegerLiteral(
                        line: 1,
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
                    line: 1,
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('continue 2;')->parse(),
            expected: [
                new Statements\ContinueStatement(
                    line: 1,
                    level: new Literals\IntegerLiteral(
                        line: 1,
                        value: 2,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testDecrementExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo--;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\DecrementExpression(
                        line: 1,
                        name: 'foo',
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
                    line: 1,
                    expression: new Literals\FloatLiteral(
                        line: 1,
                        value: 3.14,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testFunctionStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('fn foo() { print "Hello, World!"; }')->parse(),
            expected: [
                new Statements\FunctionStatement(
                    line: 1,
                    name: 'foo',
                    parameters: [],
                    statements: [
                        new Statements\PrintStatement(
                            line: 1,
                            expression: new Literals\StringLiteral(
                                line: 1,
                                value: 'Hello, World!',
                            ),
                        ),
                    ],
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('fn foo(bar, baz) { print "Hello, World!"; }')->parse(),
            expected: [
                new Statements\FunctionStatement(
                    line: 1,
                    name: 'foo',
                    parameters: [
                        new Parameter(
                            line: 1,
                            name: 'bar',
                        ),
                        new Parameter(
                            line: 1,
                            name: 'baz',
                        ),
                    ],
                    statements: [
                        new Statements\PrintStatement(
                            line: 1,
                            expression: new Literals\StringLiteral(
                                line: 1,
                                value: 'Hello, World!',
                            ),
                        ),
                    ],
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
                    line: 1,
                    expression: new Expressions\FunctionCallExpression(
                        line: 1,
                        name: 'foo',
                        arguments: [],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('foo(123, );')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\FunctionCallExpression(
                        line: 1,
                        name: 'foo',
                        arguments: [
                            new Literals\IntegerLiteral(
                                line: 1,
                                value: 123,
                            ),
                        ],
                    ),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('foo(123, foo("bar"));')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\FunctionCallExpression(
                        line: 1,
                        name: 'foo',
                        arguments: [
                            new Literals\IntegerLiteral(
                                line: 1,
                                value: 123,
                            ),
                            new Expressions\FunctionCallExpression(
                                line: 1,
                                name: 'foo',
                                arguments: [
                                    new Literals\StringLiteral(
                                        line: 1,
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
    public function testIdentifierExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\IdentifierExpression(
                        line: 1,
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
            actual: self::createParser('if (foo) { break; }')->parse(),
            expected: [
                new Statements\IfStatement(
                    line: 1,
                    condition: new Expressions\IdentifierExpression(
                        line: 1,
                        name: 'foo',
                    ),
                    thenStatements: [
                        new Statements\BreakStatement(
                            line: 1,
                        ),
                    ],
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('if (foo) { break; } else { continue; }')->parse(),
            expected: [
                new Statements\IfStatement(
                    line: 1,
                    condition: new Expressions\IdentifierExpression(
                        line: 1,
                        name: 'foo',
                    ),
                    thenStatements: [
                        new Statements\BreakStatement(
                            line: 1,
                        ),
                    ],
                    elseStatements: [
                        new Statements\ContinueStatement(
                            line: 1,
                        ),
                    ],
                ),
            ],
        );
    }

    #[Test]
    public function testIncrementExpression(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo++;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    line: 1,
                    expression: new Expressions\IncrementExpression(
                        line: 1,
                        name: 'foo',
                    ),
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
                    line: 1,
                    expression: new Literals\IntegerLiteral(
                        line: 1,
                        value: 123,
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
                    line: 1,
                    expression: new Literals\NullLiteral(
                        line: 1,
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testPrintStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('print 123;')->parse(),
            expected: [
                new Statements\PrintStatement(
                    line: 1,
                    expression: new Literals\IntegerLiteral(
                        line: 1,
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
                    line: 1,
                    expression: null,
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('return 123;')->parse(),
            expected: [
                new Statements\ReturnStatement(
                    line: 1,
                    expression: new Literals\IntegerLiteral(
                        line: 1,
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
                    line: 1,
                    expression: new Literals\StringLiteral(
                        line: 1,
                        value: 'Hello, World!',
                    ),
                ),
            ],
        );
    }

    #[Test]
    public function testVarStatement(): void
    {
        $this->assertEquals(
            actual: self::createParser('var foo = 123;')->parse(),
            expected: [
                new Statements\VarStatement(
                    line: 1,
                    name: 'foo',
                    expression: new Literals\IntegerLiteral(
                        line: 1,
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
                    line: 1,
                    condition: new Expressions\IdentifierExpression(
                        line: 1,
                        name: 'foo',
                    ),
                    statements: [
                        new Statements\BreakStatement(
                            line: 1,
                        ),
                    ],
                ),
            ],
        );
    }

    private static function createParser(string $input): ParserInterface
    {
        $Tokenizer = new Tokenizer($input);
        $parser = new Parser($Tokenizer);

        return $parser;
    }
}
