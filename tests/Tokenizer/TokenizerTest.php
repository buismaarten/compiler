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
            expected: new Token(TokenType::Assign, 1),
        );
    }

    #[Test]
    public function testAsterisk(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('*')->next(),
            expected: new Token(TokenType::Asterisk, 1),
        );
    }

    #[Test]
    public function testColon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(':')->next(),
            expected: new Token(TokenType::Colon, 1),
        );
    }

    #[Test]
    public function testComma(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(',')->next(),
            expected: new Token(TokenType::Comma, 1),
        );
    }

    #[Test]
    public function testComment(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('// This is a comment')->next(),
            expected: new Token(TokenType::Eof, 1),
        );
    }

    #[Test]
    public function testDecrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('--')->next(),
            expected: new Token(TokenType::Decrement, 1),
        );
    }

    #[Test]
    public function testExclamationMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!')->next(),
            expected: new Token(TokenType::ExclamationMark, 1),
        );
    }

    #[Test]
    public function testExponent(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('**')->next(),
            expected: new Token(TokenType::Exponent, 1),
        );
    }

    #[Test]
    public function testFloat(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('3.14')->next(),
            expected: new Token(TokenType::Float, 1, '3.14'),
        );
    }

    #[Test]
    public function testGreaterThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>')->next(),
            expected: new Token(TokenType::GreaterThan, 1),
        );
    }

    #[Test]
    public function testGreaterThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>=')->next(),
            expected: new Token(TokenType::GreaterThanEqual, 1),
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('foo_123')->next(),
            expected: new Token(TokenType::Identifier, 1, 'foo_123'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('_foo123')->next(),
            expected: new Token(TokenType::Identifier, 1, '_foo123'),
        );
    }

    #[Test]
    public function testIncrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('++')->next(),
            expected: new Token(TokenType::Increment, 1),
        );
    }

    #[Test]
    public function testInteger(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('123')->next(),
            expected: new Token(TokenType::Integer, 1, '123'),
        );
    }

    #[Test]
    public function testKeyword(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('break')->next(),
            expected: new Token(TokenType::KeywordBreak, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('const')->next(),
            expected: new Token(TokenType::KeywordConst, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('continue')->next(),
            expected: new Token(TokenType::KeywordContinue, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('else')->next(),
            expected: new Token(TokenType::KeywordElse, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('false')->next(),
            expected: new Token(TokenType::KeywordFalse, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('fn')->next(),
            expected: new Token(TokenType::KeywordFn, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('if')->next(),
            expected: new Token(TokenType::KeywordIf, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('null')->next(),
            expected: new Token(TokenType::KeywordNull, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('print')->next(),
            expected: new Token(TokenType::KeywordPrint, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('return')->next(),
            expected: new Token(TokenType::KeywordReturn, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('true')->next(),
            expected: new Token(TokenType::KeywordTrue, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('var')->next(),
            expected: new Token(TokenType::KeywordVar, 1),
        );
    }

    #[Test]
    public function testLeftBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('{')->next(),
            expected: new Token(TokenType::LeftBrace, 1),
        );
    }

    #[Test]
    public function testLeftBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('[')->next(),
            expected: new Token(TokenType::LeftBracket, 1),
        );
    }

    #[Test]
    public function testLeftParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('(')->next(),
            expected: new Token(TokenType::LeftParenthesis, 1),
        );
    }

    #[Test]
    public function testLessThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<')->next(),
            expected: new Token(TokenType::LessThan, 1),
        );
    }

    #[Test]
    public function testLessThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=')->next(),
            expected: new Token(TokenType::LessThanEqual, 1),
        );
    }

    #[Test]
    public function testMinus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('-')->next(),
            expected: new Token(TokenType::Minus, 1),
        );
    }

    #[Test]
    public function testPercent(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('%')->next(),
            expected: new Token(TokenType::Percent, 1),
        );
    }

    #[Test]
    public function testPlus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('+')->next(),
            expected: new Token(TokenType::Plus, 1),
        );
    }

    #[Test]
    public function testQuestionMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('?')->next(),
            expected: new Token(TokenType::QuestionMark, 1),
        );
    }

    #[Test]
    public function testRightBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('}')->next(),
            expected: new Token(TokenType::RightBrace, 1),
        );
    }

    #[Test]
    public function testRightBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(']')->next(),
            expected: new Token(TokenType::RightBracket, 1),
        );
    }

    #[Test]
    public function testRightParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(')')->next(),
            expected: new Token(TokenType::RightParenthesis, 1),
        );
    }

    #[Test]
    public function testSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(';')->next(),
            expected: new Token(TokenType::Semicolon, 1),
        );
    }

    #[Test]
    public function testShiftLeft(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<<')->next(),
            expected: new Token(TokenType::ShiftLeft, 1),
        );
    }

    #[Test]
    public function testShiftRight(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>>')->next(),
            expected: new Token(TokenType::ShiftRight, 1),
        );
    }

    #[Test]
    public function testSlash(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('/')->next(),
            expected: new Token(TokenType::Slash, 1),
        );
    }

    #[Test]
    public function testSpaceship(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=>')->next(),
            expected: new Token(TokenType::Spaceship, 1),
        );
    }

    #[Test]
    public function testString(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('"Hello, World!"')->next(),
            expected: new Token(TokenType::String, 1, 'Hello, World!'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\'"')->next(),
            expected: new Token(TokenType::String, 1, "'"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\""')->next(),
            expected: new Token(TokenType::String, 1, '"'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\\\\"')->next(),
            expected: new Token(TokenType::String, 1, '\\'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\0"')->next(),
            expected: new Token(TokenType::String, 1, "\0"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\f"')->next(),
            expected: new Token(TokenType::String, 1, "\f"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\n"')->next(),
            expected: new Token(TokenType::String, 1, "\n"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\r"')->next(),
            expected: new Token(TokenType::String, 1, "\r"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\t"')->next(),
            expected: new Token(TokenType::String, 1, "\t"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\u1F600"')->next(),
            expected: new Token(TokenType::String, 1, '😀'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\v"')->next(),
            expected: new Token(TokenType::String, 1, "\v"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\p"')->next(),
            expected: new Token(TokenType::String, 1, '\p'),
        );
    }

    #[Test]
    public function testTilde(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('~')->next(),
            expected: new Token(TokenType::Tilde, 1),
        );
    }

    #[Test]
    public function testWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(" \t\nfoo")->next(),
            expected: new Token(TokenType::Identifier, 2, 'foo'),
        );
    }

    private static function createTokenizer(string $input): TokenizerInterface
    {
        return new Tokenizer($input);
    }
}
