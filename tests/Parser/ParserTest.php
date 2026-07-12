<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Parser\Parser;
use Package\Tests\Testing\SnapshotAssertions;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testArrayExpression(): void
    {
        $this->assertParserSnapshot('[1, 2, 3];');
        $this->assertParserSnapshot('[1, 2, [3, 4]];');
    }

    #[Test]
    public function testArrayType(): void
    {
        $this->assertParserSnapshot('var foo: int[] = [123, 456];');
        $this->assertParserSnapshot('var foo: int[][] = [[123, 456]];');
        $this->assertParserSnapshot('var foo: int[]? = null;');
        $this->assertParserSnapshot('var foo: int[]?[]? = null;');
    }

    #[Test]
    public function testAssignmentExpression(): void
    {
        $this->assertParserSnapshot('foo = 123;');
    }

    #[Test]
    public function testBinaryExpression(): void
    {
        $this->assertParserSnapshot('1 + 2 * 3;');
    }

    #[Test]
    public function testBooleanLiteral(): void
    {
        $this->assertParserSnapshot('false;');
        $this->assertParserSnapshot('true;');
    }

    #[Test]
    public function testBreakStatement(): void
    {
        $this->assertParserSnapshot('break;');
        $this->assertParserSnapshot('break 2;');
    }

    #[Test]
    public function testConstStatement(): void
    {
        $this->assertParserSnapshot('const foo: int = 123;');
        $this->assertParserSnapshot('const foo: xyz = 123;');
    }

    #[Test]
    public function testContinueStatement(): void
    {
        $this->assertParserSnapshot('continue;');
        $this->assertParserSnapshot('continue 2;');
    }

    #[Test]
    public function testFloatLiteral(): void
    {
        $this->assertParserSnapshot('3.14;');
    }

    #[Test]
    public function testFunctionCallExpression(): void
    {
        $this->assertParserSnapshot('foo();');
        $this->assertParserSnapshot('foo(123, "bar");');
    }

    #[Test]
    public function testFunctionStatement(): void
    {
        $this->assertParserSnapshot('fn foo(): void { print "Hello, World!"; }');
        $this->assertParserSnapshot('fn foo(bar: int, baz: xyz): xyz { return baz; }');
    }

    #[Test]
    public function testIdentifierExpression(): void
    {
        $this->assertParserSnapshot('foo;');
    }

    #[Test]
    public function testIfStatement(): void
    {
        $this->assertParserSnapshot('if (foo) { return 1; }');
        $this->assertParserSnapshot('if (foo) { return 1; } else { return 2; }');
    }

    #[Test]
    public function testIntegerLiteral(): void
    {
        $this->assertParserSnapshot('123;');
    }

    #[Test]
    public function testNamedType(): void
    {
        $this->assertParserSnapshot('var foo: xyz = 123;');
        $this->assertParserSnapshot('fn foo(bar: xyz): xyz { return bar; }');
    }

    #[Test]
    public function testNullableType(): void
    {
        $this->assertParserSnapshot('var foo: int? = null;');
        $this->assertParserSnapshot('var foo: int?[] = null;');
        $this->assertParserSnapshot('var foo: int?[]? = null;');
    }

    #[Test]
    public function testNullLiteral(): void
    {
        $this->assertParserSnapshot('null;');
    }

    #[Test]
    public function testPostfixUnaryExpression(): void
    {
        $this->assertParserSnapshot('foo--;');
        $this->assertParserSnapshot('foo++;');
    }

    #[Test]
    public function testPrefixUnaryExpression(): void
    {
        $this->assertParserSnapshot('~foo;');
        $this->assertParserSnapshot('!foo;');
        $this->assertParserSnapshot('--foo;');
        $this->assertParserSnapshot('++foo;');
        $this->assertParserSnapshot('-foo;');
        $this->assertParserSnapshot('+foo;');
    }

    #[Test]
    public function testPrintStatement(): void
    {
        $this->assertParserSnapshot('print 123;');
    }

    #[Test]
    public function testReturnStatement(): void
    {
        $this->assertParserSnapshot('return;');
        $this->assertParserSnapshot('return 123;');
    }

    #[Test]
    public function testStringLiteral(): void
    {
        $this->assertParserSnapshot('"Hello, World!";');
    }

    #[Test]
    public function testTupleType(): void
    {
        $this->assertParserSnapshot('var foo: (int, int) = (123, 456);');
        $this->assertParserSnapshot('var foo: (int, int)[] = [(123, 456)];');
        $this->assertParserSnapshot('var foo: (int, int)? = (123, 456);');
    }

    #[Test]
    public function testVarStatement(): void
    {
        $this->assertParserSnapshot('var foo: int = 123;');
        $this->assertParserSnapshot('var foo: xyz = 123;');
    }

    #[Test]
    public function testWhileStatement(): void
    {
        $this->assertParserSnapshot('while (foo) { break; }');
    }

    private function assertParserSnapshot(string $input): void
    {
        $tokenizer = new Tokenizer($input);
        $parser = new Parser($tokenizer);

        $this->assertMatchesSnapshot($parser->parse());
    }
}
