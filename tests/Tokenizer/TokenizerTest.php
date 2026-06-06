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
            expected: new Token(TokenType::T_ASSIGN, 1),
        );
    }

    #[Test]
    public function testAsterisk(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('*')->next(),
            expected: new Token(TokenType::T_ASTERISK, 1),
        );
    }

    #[Test]
    public function testColon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(':')->next(),
            expected: new Token(TokenType::T_COLON, 1),
        );
    }

    #[Test]
    public function testComma(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(',')->next(),
            expected: new Token(TokenType::T_COMMA, 1),
        );
    }

    #[Test]
    public function testComment(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('// This is a comment')->next(),
            expected: new Token(TokenType::T_EOF, 1),
        );
    }

    #[Test]
    public function testDecrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('--')->next(),
            expected: new Token(TokenType::T_DECREMENT, 1),
        );
    }

    #[Test]
    public function testFloat(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('3.14')->next(),
            expected: new Token(TokenType::T_FLOAT, 1, '3.14'),
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('foo_123')->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 1, 'foo_123'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('_foo123')->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 1, '_foo123'),
        );
    }

    #[Test]
    public function testIncrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('++')->next(),
            expected: new Token(TokenType::T_INCREMENT, 1),
        );
    }

    #[Test]
    public function testInteger(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('123')->next(),
            expected: new Token(TokenType::T_INTEGER, 1, '123'),
        );
    }

    #[Test]
    public function testKeyword(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('break')->next(),
            expected: new Token(TokenType::T_KEYWORD_BREAK, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('const')->next(),
            expected: new Token(TokenType::T_KEYWORD_CONST, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('continue')->next(),
            expected: new Token(TokenType::T_KEYWORD_CONTINUE, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('else')->next(),
            expected: new Token(TokenType::T_KEYWORD_ELSE, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('false')->next(),
            expected: new Token(TokenType::T_KEYWORD_FALSE, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('fn')->next(),
            expected: new Token(TokenType::T_KEYWORD_FN, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('if')->next(),
            expected: new Token(TokenType::T_KEYWORD_IF, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('null')->next(),
            expected: new Token(TokenType::T_KEYWORD_NULL, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('print')->next(),
            expected: new Token(TokenType::T_KEYWORD_PRINT, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('return')->next(),
            expected: new Token(TokenType::T_KEYWORD_RETURN, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('true')->next(),
            expected: new Token(TokenType::T_KEYWORD_TRUE, 1),
        );

        $this->assertEquals(
            actual: self::createTokenizer('var')->next(),
            expected: new Token(TokenType::T_KEYWORD_VAR, 1),
        );
    }

    #[Test]
    public function testLeftBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('{')->next(),
            expected: new Token(TokenType::T_LEFT_BRACE, 1),
        );
    }

    #[Test]
    public function testLeftBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('[')->next(),
            expected: new Token(TokenType::T_LEFT_BRACKET, 1),
        );
    }

    #[Test]
    public function testLeftParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('(')->next(),
            expected: new Token(TokenType::T_LEFT_PARENTHESIS, 1),
        );
    }

    #[Test]
    public function testMinus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('-')->next(),
            expected: new Token(TokenType::T_MINUS, 1),
        );
    }

    #[Test]
    public function testPlus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('+')->next(),
            expected: new Token(TokenType::T_PLUS, 1),
        );
    }

    #[Test]
    public function testQuestionMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('?')->next(),
            expected: new Token(TokenType::T_QUESTION_MARK, 1),
        );
    }

    #[Test]
    public function testRightBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('}')->next(),
            expected: new Token(TokenType::T_RIGHT_BRACE, 1),
        );
    }

    #[Test]
    public function testRightBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(']')->next(),
            expected: new Token(TokenType::T_RIGHT_BRACKET, 1),
        );
    }

    #[Test]
    public function testRightParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(')')->next(),
            expected: new Token(TokenType::T_RIGHT_PARENTHESIS, 1),
        );
    }

    #[Test]
    public function testSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(';')->next(),
            expected: new Token(TokenType::T_SEMICOLON, 1),
        );
    }

    #[Test]
    public function testSlash(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('/')->next(),
            expected: new Token(TokenType::T_SLASH, 1),
        );
    }

    #[Test]
    public function testString(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('"Hello, World!"')->next(),
            expected: new Token(TokenType::T_STRING, 1, 'Hello, World!'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\'"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "'"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\""')->next(),
            expected: new Token(TokenType::T_STRING, 1, '"'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\\\\"')->next(),
            expected: new Token(TokenType::T_STRING, 1, '\\'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\0"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\0"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\f"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\f"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\n"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\n"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\r"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\r"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\t"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\t"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\u1F600"')->next(),
            expected: new Token(TokenType::T_STRING, 1, '😀'),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\v"')->next(),
            expected: new Token(TokenType::T_STRING, 1, "\v"),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\p"')->next(),
            expected: new Token(TokenType::T_STRING, 1, '\p'),
        );
    }

    #[Test]
    public function testWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(" \t\nfoo")->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 2, 'foo'),
        );
    }

    private static function createTokenizer(string $input): TokenizerInterface
    {
        return new Tokenizer($input);
    }
}
