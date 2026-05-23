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
                new Statements\BreakStatement,
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
                    name: 'foo',
                    value: new Literals\IntegerLiteral(123),
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
                new Statements\ContinueStatement,
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
                    expression: new Literals\BooleanLiteral(false),
                ),
            ],
        );

        $this->assertEquals(
            actual: self::createParser('true;')->parse(),
            expected: [
                new Statements\ExpressionStatement(
                    expression: new Literals\BooleanLiteral(true),
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
                    expression: new Expressions\IdentifierExpression('foo'),
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
                    expression: new Literals\IntegerLiteral(123),
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
                    expression: new Literals\NullLiteral(),
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
                new Statements\ReturnStatement,
            ],
        );

        $this->assertEquals(
            actual: self::createParser('return 123;')->parse(),
            expected: [
                new Statements\ReturnStatement(
                    expression: new Literals\IntegerLiteral(123),
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
                    name: 'foo',
                    value: new Literals\IntegerLiteral(123),
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
                    condition: new Expressions\IdentifierExpression('foo'),
                    statements: [
                        new Statements\BreakStatement,
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
