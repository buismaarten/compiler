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
    public function testInteger(): void
    {
        $this->assertEquals(
            actual: self::createLexer('123')->next(),
            expected: new Token(TokenType::T_INTEGER, 1, '123'),
        );
    }

    #[Test]
    public function testKeyword(): void
    {
        $this->assertEquals(
            actual: self::createLexer('break')->next(),
            expected: new Token(TokenType::T_KEYWORD_BREAK, 1),
        );

        $this->assertEquals(
            actual: self::createLexer('continue')->next(),
            expected: new Token(TokenType::T_KEYWORD_CONTINUE, 1),
        );

        $this->assertEquals(
            actual: self::createLexer('false')->next(),
            expected: new Token(TokenType::T_KEYWORD_FALSE, 1),
        );

        $this->assertEquals(
            actual: self::createLexer('null')->next(),
            expected: new Token(TokenType::T_KEYWORD_NULL, 1),
        );

        $this->assertEquals(
            actual: self::createLexer('true')->next(),
            expected: new Token(TokenType::T_KEYWORD_TRUE, 1),
        );
    }

    #[Test]
    public function testSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createLexer(';')->next(),
            expected: new Token(TokenType::T_SEMICOLON, 1),
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
