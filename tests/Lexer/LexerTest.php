<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Lexer;

use Package\Lexer\Lexer;
use Package\Lexer\LexerInterface;
use Package\Token\Token;
use Package\Token\TokenType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    #[Test]
    public function testGetTokens(): void
    {
        $this->assertEquals(
            actual: iterator_to_array(self::createLexer('foo 123')->getTokens()),
            expected: [
                new Token(TokenType::T_IDENTIFIER, 1, 'foo'),
                new Token(TokenType::T_NUMBER, 1, '123'),
            ],
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createLexer('foo_123')->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 1, 'foo_123'),
        );

        $this->assertEquals(
            actual: self::createLexer('_foo123')->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 1, '_foo123'),
        );
    }

    #[Test]
    public function testKeywordFalse(): void
    {
        $this->assertEquals(
            actual: self::createLexer('false')->next(),
            expected: new Token(TokenType::T_KEYWORD_FALSE, 1),
        );
    }

    #[Test]
    public function testKeywordNull(): void
    {
        $this->assertEquals(
            actual: self::createLexer('null')->next(),
            expected: new Token(TokenType::T_KEYWORD_NULL, 1),
        );
    }

    #[Test]
    public function testKeywordTrue(): void
    {
        $this->assertEquals(
            actual: self::createLexer('true')->next(),
            expected: new Token(TokenType::T_KEYWORD_TRUE, 1),
        );
    }

    #[Test]
    public function testMultiply(): void
    {
        $this->assertEquals(
            actual: self::createLexer('*')->next(),
            expected: new Token(TokenType::T_MULTIPLY, 1),
        );
    }

    #[Test]
    public function testNumber(): void
    {
        $this->assertEquals(
            actual: self::createLexer('123')->next(),
            expected: new Token(TokenType::T_NUMBER, 1, '123'),
        );
    }

    #[Test]
    public function testPlus(): void
    {
        $this->assertEquals(
            actual: self::createLexer('+')->next(),
            expected: new Token(TokenType::T_PLUS, 1),
        );
    }

    #[Test]
    public function testWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createLexer(" \t\nfoo")->next(),
            expected: new Token(TokenType::T_IDENTIFIER, 2, 'foo'),
        );
    }

    private static function createLexer(string $input): LexerInterface
    {
        return new Lexer($input);
    }
}
