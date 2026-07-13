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
    public function testParseArrayExpression(): void
    {
        $this->assertSnapshot('[1, 2, 3];');
        $this->assertSnapshot('[1, 2, [3, 4]];');
    }

    #[Test]
    public function testParseArrayType(): void
    {
        $this->assertSnapshot('var foo: int[] = [123, 456];');
        $this->assertSnapshot('var foo: int[][] = [[123, 456]];');
        $this->assertSnapshot('var foo: int[]? = null;');
        $this->assertSnapshot('var foo: int[]?[]? = null;');
    }

    #[Test]
    public function testParseAssignmentExpression(): void
    {
        $this->assertSnapshot('foo = 123;');
    }

    #[Test]
    public function testParseBinaryExpression(): void
    {
        $this->assertSnapshot('1 + 2 * 3;');
    }

    #[Test]
    public function testParseBooleanLiteral(): void
    {
        $this->assertSnapshot('false;');
        $this->assertSnapshot('true;');
    }

    #[Test]
    public function testParseBreakStatement(): void
    {
        $this->assertSnapshot('break;');
        $this->assertSnapshot('break 2;');
    }

    #[Test]
    public function testParseConstStatement(): void
    {
        $this->assertSnapshot('const foo: int = 123;');
        $this->assertSnapshot('const foo: xyz = 123;');
    }

    #[Test]
    public function testParseContinueStatement(): void
    {
        $this->assertSnapshot('continue;');
        $this->assertSnapshot('continue 2;');
    }

    #[Test]
    public function testParseFloatLiteral(): void
    {
        $this->assertSnapshot('3.14;');
    }

    #[Test]
    public function testParseFunctionCallExpression(): void
    {
        $this->assertSnapshot('foo();');
        $this->assertSnapshot('foo(123, "bar");');
    }

    #[Test]
    public function testParseFunctionStatement(): void
    {
        $this->assertSnapshot('fn foo(): void { print "Hello, World!"; }');
        $this->assertSnapshot('fn foo(bar: int, baz: xyz): xyz { return baz; }');
    }

    #[Test]
    public function testParseIdentifierExpression(): void
    {
        $this->assertSnapshot('foo;');
    }

    #[Test]
    public function testParseIfStatement(): void
    {
        $this->assertSnapshot('if (foo) { return 1; }');
        $this->assertSnapshot('if (foo) { return 1; } else { return 2; }');
    }

    #[Test]
    public function testParseIntegerLiteral(): void
    {
        $this->assertSnapshot('123;');
    }

    #[Test]
    public function testParseNamedType(): void
    {
        $this->assertSnapshot('var foo: xyz = 123;');
        $this->assertSnapshot('fn foo(bar: xyz): xyz { return bar; }');
    }

    #[Test]
    public function testParseNullableType(): void
    {
        $this->assertSnapshot('var foo: int? = null;');
        $this->assertSnapshot('var foo: int?[] = null;');
        $this->assertSnapshot('var foo: int?[]? = null;');
    }

    #[Test]
    public function testParseNullLiteral(): void
    {
        $this->assertSnapshot('null;');
    }

    #[Test]
    public function testParsePostfixUnaryExpression(): void
    {
        $this->assertSnapshot('foo--;');
        $this->assertSnapshot('foo++;');
    }

    #[Test]
    public function testParsePrefixUnaryExpression(): void
    {
        $this->assertSnapshot('~foo;');
        $this->assertSnapshot('!foo;');
        $this->assertSnapshot('--foo;');
        $this->assertSnapshot('++foo;');
        $this->assertSnapshot('-foo;');
        $this->assertSnapshot('+foo;');
    }

    #[Test]
    public function testParsePrintStatement(): void
    {
        $this->assertSnapshot('print 123;');
    }

    #[Test]
    public function testParseReturnStatement(): void
    {
        $this->assertSnapshot('return;');
        $this->assertSnapshot('return 123;');
    }

    #[Test]
    public function testParseStringLiteral(): void
    {
        $this->assertSnapshot('"Hello, World!";');
    }

    #[Test]
    public function testParseTupleType(): void
    {
        $this->assertSnapshot('var foo: (int, int) = (123, 456);');
        $this->assertSnapshot('var foo: (int, int)[] = [(123, 456)];');
        $this->assertSnapshot('var foo: (int, int)? = (123, 456);');
    }

    #[Test]
    public function testParseVarStatement(): void
    {
        $this->assertSnapshot('var foo: int = 123;');
        $this->assertSnapshot('var foo: xyz = 123;');
    }

    #[Test]
    public function testParseWhileStatement(): void
    {
        $this->assertSnapshot('while (foo) { break; }');
    }

    private function assertSnapshot(string $input): void
    {
        $tokenizer = new Tokenizer($input);
        $parser = new Parser($tokenizer);

        $this->assertMatchesSnapshot($parser->parse());
    }
}
