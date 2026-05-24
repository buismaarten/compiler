<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Lexer\Lexer;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Statements;
use Package\Parser\Parser;
use Package\Parser\ParserInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
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
        $lexer = new Lexer($input);
        $parser = new Parser($lexer);

        return $parser;
    }
}
