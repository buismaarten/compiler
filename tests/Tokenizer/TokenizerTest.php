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
            expected: new Token(TokenType::Assign, 1, 0, 1),
        );
    }

    #[Test]
    public function testBitwiseAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&')->next(),
            expected: new Token(TokenType::BitwiseAnd, 1, 0, 1),
        );
    }

    #[Test]
    public function testBitwiseNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('~')->next(),
            expected: new Token(TokenType::BitwiseNot, 1, 0, 1),
        );
    }

    #[Test]
    public function testBitwiseOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('|')->next(),
            expected: new Token(TokenType::BitwiseOr, 1, 0, 1),
        );
    }

    #[Test]
    public function testBitwiseXor(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('^')->next(),
            expected: new Token(TokenType::BitwiseXor, 1, 0, 1),
        );
    }

    #[Test]
    public function testBooleanAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&&')->next(),
            expected: new Token(TokenType::BooleanAnd, 1, 0, 2),
        );
    }

    #[Test]
    public function testBooleanNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!')->next(),
            expected: new Token(TokenType::BooleanNot, 1, 0, 1),
        );
    }

    #[Test]
    public function testBooleanOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('||')->next(),
            expected: new Token(TokenType::BooleanOr, 1, 0, 2),
        );
    }

    #[Test]
    public function testColon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(':')->next(),
            expected: new Token(TokenType::Colon, 1, 0, 1),
        );
    }

    #[Test]
    public function testComma(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(',')->next(),
            expected: new Token(TokenType::Comma, 1, 0, 1),
        );
    }

    #[Test]
    public function testComment(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('// This is a comment')->next(),
            expected: new Token(TokenType::Eof, 1, 20, 20),
        );
    }

    #[Test]
    public function testDecrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('--')->next(),
            expected: new Token(TokenType::Decrement, 1, 0, 2),
        );
    }

    #[Test]
    public function testDivide(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('/')->next(),
            expected: new Token(TokenType::Divide, 1, 0, 1),
        );
    }

    #[Test]
    public function testEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('==')->next(),
            expected: new Token(TokenType::Equal, 1, 0, 2),
        );
    }

    #[Test]
    public function testFloat(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('3.14')->next(),
            expected: new Token(TokenType::Float, 1, 0, 4, '3.14'),
        );
    }

    #[Test]
    public function testGreaterThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>')->next(),
            expected: new Token(TokenType::GreaterThan, 1, 0, 1),
        );
    }

    #[Test]
    public function testGreaterThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>=')->next(),
            expected: new Token(TokenType::GreaterThanEqual, 1, 0, 2),
        );
    }

    #[Test]
    public function testIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('===')->next(),
            expected: new Token(TokenType::Identical, 1, 0, 3),
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('foo_123')->next(),
            expected: new Token(TokenType::Identifier, 1, 0, 7, 'foo_123'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('_foo123')->next(),
            expected: new Token(TokenType::Identifier, 1, 0, 7, '_foo123'),
        );
    }

    #[Test]
    public function testIncrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('++')->next(),
            expected: new Token(TokenType::Increment, 1, 0, 2),
        );
    }

    #[Test]
    public function testInteger(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('123')->next(),
            expected: new Token(TokenType::Integer, 1, 0, 3, '123'),
        );
    }

    #[Test]
    public function testKeyword(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('break')->next(),
            expected: new Token(TokenType::KeywordBreak, 1, 0, 5),
        );

        $this->assertEquals(
            actual: self::createTokenizer('const')->next(),
            expected: new Token(TokenType::KeywordConst, 1, 0, 5),
        );

        $this->assertEquals(
            actual: self::createTokenizer('continue')->next(),
            expected: new Token(TokenType::KeywordContinue, 1, 0, 8),
        );

        $this->assertEquals(
            actual: self::createTokenizer('else')->next(),
            expected: new Token(TokenType::KeywordElse, 1, 0, 4),
        );

        $this->assertEquals(
            actual: self::createTokenizer('false')->next(),
            expected: new Token(TokenType::KeywordFalse, 1, 0, 5),
        );

        $this->assertEquals(
            actual: self::createTokenizer('fn')->next(),
            expected: new Token(TokenType::KeywordFn, 1, 0, 2),
        );

        $this->assertEquals(
            actual: self::createTokenizer('if')->next(),
            expected: new Token(TokenType::KeywordIf, 1, 0, 2),
        );

        $this->assertEquals(
            actual: self::createTokenizer('null')->next(),
            expected: new Token(TokenType::KeywordNull, 1, 0, 4),
        );

        $this->assertEquals(
            actual: self::createTokenizer('print')->next(),
            expected: new Token(TokenType::KeywordPrint, 1, 0, 5),
        );

        $this->assertEquals(
            actual: self::createTokenizer('return')->next(),
            expected: new Token(TokenType::KeywordReturn, 1, 0, 6),
        );

        $this->assertEquals(
            actual: self::createTokenizer('true')->next(),
            expected: new Token(TokenType::KeywordTrue, 1, 0, 4),
        );

        $this->assertEquals(
            actual: self::createTokenizer('var')->next(),
            expected: new Token(TokenType::KeywordVar, 1, 0, 3),
        );
    }

    #[Test]
    public function testLeftBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('{')->next(),
            expected: new Token(TokenType::LeftBrace, 1, 0, 1),
        );
    }

    #[Test]
    public function testLeftBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('[')->next(),
            expected: new Token(TokenType::LeftBracket, 1, 0, 1),
        );
    }

    #[Test]
    public function testLeftParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('(')->next(),
            expected: new Token(TokenType::LeftParenthesis, 1, 0, 1),
        );
    }

    #[Test]
    public function testLessThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<')->next(),
            expected: new Token(TokenType::LessThan, 1, 0, 1),
        );
    }

    #[Test]
    public function testLessThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=')->next(),
            expected: new Token(TokenType::LessThanEqual, 1, 0, 2),
        );
    }

    #[Test]
    public function testMinus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('-')->next(),
            expected: new Token(TokenType::Minus, 1, 0, 1),
        );
    }

    #[Test]
    public function testModulo(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('%')->next(),
            expected: new Token(TokenType::Modulo, 1, 0, 1),
        );
    }

    #[Test]
    public function testMultiply(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('*')->next(),
            expected: new Token(TokenType::Multiply, 1, 0, 1),
        );
    }

    #[Test]
    public function testNotEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!=')->next(),
            expected: new Token(TokenType::NotEqual, 1, 0, 2),
        );
    }

    #[Test]
    public function testNotIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!==')->next(),
            expected: new Token(TokenType::NotIdentical, 1, 0, 3),
        );
    }

    #[Test]
    public function testPlus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('+')->next(),
            expected: new Token(TokenType::Plus, 1, 0, 1),
        );
    }

    #[Test]
    public function testQuestionMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('?')->next(),
            expected: new Token(TokenType::QuestionMark, 1, 0, 1),
        );
    }

    #[Test]
    public function testRightBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('}')->next(),
            expected: new Token(TokenType::RightBrace, 1, 0, 1),
        );
    }

    #[Test]
    public function testRightBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(']')->next(),
            expected: new Token(TokenType::RightBracket, 1, 0, 1),
        );
    }

    #[Test]
    public function testRightParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(')')->next(),
            expected: new Token(TokenType::RightParenthesis, 1, 0, 1),
        );
    }

    #[Test]
    public function testSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(';')->next(),
            expected: new Token(TokenType::Semicolon, 1, 0, 1),
        );
    }

    #[Test]
    public function testShiftLeft(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<<')->next(),
            expected: new Token(TokenType::ShiftLeft, 1, 0, 2),
        );
    }

    #[Test]
    public function testShiftRight(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>>')->next(),
            expected: new Token(TokenType::ShiftRight, 1, 0, 2),
        );
    }

    #[Test]
    public function testSpaceship(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=>')->next(),
            expected: new Token(TokenType::Spaceship, 1, 0, 3),
        );
    }

    #[Test]
    public function testString(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('"Hello, World!"')->next(),
            expected: new Token(TokenType::String, 1, 0, 15, 'Hello, World!'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\'"')->next(),
            expected: new Token(TokenType::String, 1, 0, 3, "'"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\""')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, '"'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\\\\"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, '\\'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\0"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\0"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\f"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\f"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\n"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\n"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\r"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\r"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\t"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\t"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\u1F600"')->next(),
            expected: new Token(TokenType::String, 1, 0, 9, '😀'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\v"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, "\v"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\p"')->next(),
            expected: new Token(TokenType::String, 1, 0, 4, '\p'),
        );
    }

    #[Test]
    public function testWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(" \t\nfoo")->next(),
            expected: new Token(TokenType::Identifier, 2, 3, 6, 'foo'),
        );
    }

    private static function createTokenizer(string $input): TokenizerInterface
    {
        return new Tokenizer($input);
    }
}
