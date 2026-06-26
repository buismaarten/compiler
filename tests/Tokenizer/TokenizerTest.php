<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Tokenizer;

use Package\Tokenizer\Token;
use Package\Tokenizer\Tokenizer;
use Package\Tokenizer\TokenizerInterface;
use Package\Tokenizer\TokenType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    #[Test]
    public function testAssign(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('=')->next(),
            expected: new Token(
                type: TokenType::Assign,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBitwiseAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&')->next(),
            expected: new Token(
                type: TokenType::BitwiseAnd,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBitwiseNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('~')->next(),
            expected: new Token(
                type: TokenType::BitwiseNot,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBitwiseOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('|')->next(),
            expected: new Token(
                type: TokenType::BitwiseOr,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBitwiseXor(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('^')->next(),
            expected: new Token(
                type: TokenType::BitwiseXor,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBooleanAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&&')->next(),
            expected: new Token(
                type: TokenType::BooleanAnd,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testBooleanNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!')->next(),
            expected: new Token(
                type: TokenType::BooleanNot,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testBooleanOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('||')->next(),
            expected: new Token(
                type: TokenType::BooleanOr,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testColon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(':')->next(),
            expected: new Token(
                type: TokenType::Colon,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testComma(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(',')->next(),
            expected: new Token(
                type: TokenType::Comma,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testComment(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('// This is a comment')->next(),
            expected: new Token(
                type: TokenType::Eof,
                line: 1,
                offsetStart: 20,
                offsetEnd: 20,
            ),
        );
    }

    #[Test]
    public function testDecrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('--')->next(),
            expected: new Token(
                type: TokenType::Decrement,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testDivide(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('/')->next(),
            expected: new Token(
                type: TokenType::Divide,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('==')->next(),
            expected: new Token(
                type: TokenType::Equal,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testFloat(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('3.14')->next(),
            expected: new Token(
                type: TokenType::Float,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: '3.14',
            ),
        );
    }

    #[Test]
    public function testGreaterThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>')->next(),
            expected: new Token(
                type: TokenType::GreaterThan,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testGreaterThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>=')->next(),
            expected: new Token(
                type: TokenType::GreaterThanEqual,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('===')->next(),
            expected: new Token(
                type: TokenType::Identical,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
            ),
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('foo_123')->next(),
            expected: new Token(
                type: TokenType::Identifier,
                line: 1,
                offsetStart: 0,
                offsetEnd: 7,
                value: 'foo_123',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('_foo123')->next(),
            expected: new Token(
                type: TokenType::Identifier,
                line: 1,
                offsetStart: 0,
                offsetEnd: 7,
                value: '_foo123',
            ),
        );
    }

    #[Test]
    public function testIncrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('++')->next(),
            expected: new Token(
                type: TokenType::Increment,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testInteger(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('123')->next(),
            expected: new Token(
                type: TokenType::Integer,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
                value: '123',
            ),
        );
    }

    #[Test]
    public function testKeyword(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('break')->next(),
            expected: new Token(
                type: TokenType::KeywordBreak,
                line: 1,
                offsetStart: 0,
                offsetEnd: 5,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('const')->next(),
            expected: new Token(
                type: TokenType::KeywordConst,
                line: 1,
                offsetStart: 0,
                offsetEnd: 5,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('continue')->next(),
            expected: new Token(
                type: TokenType::KeywordContinue,
                line: 1,
                offsetStart: 0,
                offsetEnd: 8,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('else')->next(),
            expected: new Token(
                type: TokenType::KeywordElse,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('false')->next(),
            expected: new Token(
                type: TokenType::KeywordFalse,
                line: 1,
                offsetStart: 0,
                offsetEnd: 5,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('fn')->next(),
            expected: new Token(
                type: TokenType::KeywordFn,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('if')->next(),
            expected: new Token(
                type: TokenType::KeywordIf,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('null')->next(),
            expected: new Token(
                type: TokenType::KeywordNull,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('print')->next(),
            expected: new Token(
                type: TokenType::KeywordPrint,
                line: 1,
                offsetStart: 0,
                offsetEnd: 5,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('return')->next(),
            expected: new Token(
                type: TokenType::KeywordReturn,
                line: 1,
                offsetStart: 0,
                offsetEnd: 6,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('true')->next(),
            expected: new Token(
                type: TokenType::KeywordTrue,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('var')->next(),
            expected: new Token(
                type: TokenType::KeywordVar,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
            ),
        );
    }

    #[Test]
    public function testLeftBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('{')->next(),
            expected: new Token(
                type: TokenType::LeftBrace,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testLeftBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('[')->next(),
            expected: new Token(
                type: TokenType::LeftBracket,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testLeftParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('(')->next(),
            expected: new Token(
                type: TokenType::LeftParenthesis,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testLessThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<')->next(),
            expected: new Token(
                type: TokenType::LessThan,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testLessThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=')->next(),
            expected: new Token(
                type: TokenType::LessThanEqual,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testMinus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('-')->next(),
            expected: new Token(
                type: TokenType::Minus,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testModulo(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('%')->next(),
            expected: new Token(
                type: TokenType::Modulo,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testMultiply(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('*')->next(),
            expected: new Token(
                type: TokenType::Multiply,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testNotEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!=')->next(),
            expected: new Token(
                type: TokenType::NotEqual,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testNotIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!==')->next(),
            expected: new Token(
                type: TokenType::NotIdentical,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
            ),
        );
    }

    #[Test]
    public function testPlus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('+')->next(),
            expected: new Token(
                type: TokenType::Plus,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testQuestionMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('?')->next(),
            expected: new Token(
                type: TokenType::QuestionMark,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testRightBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('}')->next(),
            expected: new Token(
                type: TokenType::RightBrace,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testRightBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(']')->next(),
            expected: new Token(
                type: TokenType::RightBracket,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testRightParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(')')->next(),
            expected: new Token(
                type: TokenType::RightParenthesis,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(';')->next(),
            expected: new Token(
                type: TokenType::Semicolon,
                line: 1,
                offsetStart: 0,
                offsetEnd: 1,
            ),
        );
    }

    #[Test]
    public function testShiftLeft(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<<')->next(),
            expected: new Token(
                type: TokenType::ShiftLeft,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testShiftRight(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>>')->next(),
            expected: new Token(
                type: TokenType::ShiftRight,
                line: 1,
                offsetStart: 0,
                offsetEnd: 2,
            ),
        );
    }

    #[Test]
    public function testSpaceship(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=>')->next(),
            expected: new Token(
                type: TokenType::Spaceship,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
            ),
        );
    }

    #[Test]
    public function testString(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('"Hello, World!"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 15,
                value: 'Hello, World!',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\'"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 3,
                value: "'",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\""')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: '"',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\\\\"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: '\\',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\0"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\0",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\f"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\f",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\n"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\n",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\r"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\r",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\t"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\t",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\u1F600"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 9,
                value: '😀',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\v"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: "\v",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\p"')->next(),
            expected: new Token(
                type: TokenType::String,
                line: 1,
                offsetStart: 0,
                offsetEnd: 4,
                value: '\p',
            ),
        );
    }

    #[Test]
    public function testWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(" \t\nfoo")->next(),
            expected: new Token(
                type: TokenType::Identifier,
                line: 2,
                offsetStart: 3,
                offsetEnd: 6,
                value: 'foo',
            ),
        );
    }

    private static function createTokenizer(string $input): TokenizerInterface
    {
        return new Tokenizer($input);
    }
}
