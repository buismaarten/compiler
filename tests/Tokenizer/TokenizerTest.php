<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Tokenizer;

use Package\Common\Location;
use Package\Tokenizer\Token;
use Package\Tokenizer\Tokenizer;
use Package\Tokenizer\TokenType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    #[Test]
    public function testTokenizeAssign(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('=')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Assign,
            ),
        );
    }

    #[Test]
    public function testTokenizeBitwiseAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::BitwiseAnd,
            ),
        );
    }

    #[Test]
    public function testTokenizeBitwiseNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('~')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::BitwiseNot,
            ),
        );
    }

    #[Test]
    public function testTokenizeBitwiseOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('|')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::BitwiseOr,
            ),
        );
    }

    #[Test]
    public function testTokenizeBitwiseXor(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('^')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::BitwiseXor,
            ),
        );
    }

    #[Test]
    public function testTokenizeBooleanAnd(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('&&')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::BooleanAnd,
            ),
        );
    }

    #[Test]
    public function testTokenizeBooleanNot(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::BooleanNot,
            ),
        );
    }

    #[Test]
    public function testTokenizeBooleanOr(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('||')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::BooleanOr,
            ),
        );
    }

    #[Test]
    public function testTokenizeColon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(':')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Colon,
            ),
        );
    }

    #[Test]
    public function testTokenizeComma(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(',')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Comma,
            ),
        );
    }

    #[Test]
    public function testTokenizeComment(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('// This is a comment')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 20,
                    endLine: 1,
                    endOffset: 20,
                ),
                type: TokenType::Eof,
            ),
        );
    }

    #[Test]
    public function testTokenizeDecrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('--')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::Decrement,
            ),
        );
    }

    #[Test]
    public function testTokenizeDivide(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('/')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Divide,
            ),
        );
    }

    #[Test]
    public function testTokenizeEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('==')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::Equal,
            ),
        );
    }

    #[Test]
    public function testTokenizeFloat(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('3.14')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::Float,
                value: '3.14',
            ),
        );
    }

    #[Test]
    public function testTokenizeGreaterThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::GreaterThan,
            ),
        );
    }

    #[Test]
    public function testTokenizeGreaterThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>=')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::GreaterThanEqual,
            ),
        );
    }

    #[Test]
    public function testTokenizeIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('===')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::Identical,
            ),
        );
    }

    #[Test]
    public function testTokenizeIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('foo_123')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 7,
                ),
                type: TokenType::Identifier,
                value: 'foo_123',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('_foo123')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 7,
                ),
                type: TokenType::Identifier,
                value: '_foo123',
            ),
        );
    }

    #[Test]
    public function testTokenizeIncrement(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('++')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::Increment,
            ),
        );
    }

    #[Test]
    public function testTokenizeInteger(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('123')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::Integer,
                value: '123',
            ),
        );
    }

    #[Test]
    public function testTokenizeKeyword(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('break')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 5,
                ),
                type: TokenType::KeywordBreak,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('const')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 5,
                ),
                type: TokenType::KeywordConst,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('continue')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 8,
                ),
                type: TokenType::KeywordContinue,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('else')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::KeywordElse,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('false')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 5,
                ),
                type: TokenType::KeywordFalse,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('fn')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::KeywordFn,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('if')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::KeywordIf,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('null')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::KeywordNull,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('print')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 5,
                ),
                type: TokenType::KeywordPrint,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('return')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 6,
                ),
                type: TokenType::KeywordReturn,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('true')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::KeywordTrue,
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('var')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::KeywordVar,
            ),
        );
    }

    #[Test]
    public function testTokenizeLeftBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('{')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::LeftBrace,
            ),
        );
    }

    #[Test]
    public function testTokenizeLeftBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('[')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::LeftBracket,
            ),
        );
    }

    #[Test]
    public function testTokenizeLeftParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('(')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::LeftParenthesis,
            ),
        );
    }

    #[Test]
    public function testTokenizeLessThan(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::LessThan,
            ),
        );
    }

    #[Test]
    public function testTokenizeLessThanEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::LessThanEqual,
            ),
        );
    }

    #[Test]
    public function testTokenizeMinus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('-')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Minus,
            ),
        );
    }

    #[Test]
    public function testTokenizeModulo(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('%')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Modulo,
            ),
        );
    }

    #[Test]
    public function testTokenizeMultiply(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('*')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Multiply,
            ),
        );
    }

    #[Test]
    public function testTokenizeNotEqual(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!=')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::NotEqual,
            ),
        );
    }

    #[Test]
    public function testTokenizeNotIdentical(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('!==')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::NotIdentical,
            ),
        );
    }

    #[Test]
    public function testTokenizePlus(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('+')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Plus,
            ),
        );
    }

    #[Test]
    public function testTokenizeQuestionMark(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('?')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::QuestionMark,
            ),
        );
    }

    #[Test]
    public function testTokenizeRightBrace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('}')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::RightBrace,
            ),
        );
    }

    #[Test]
    public function testTokenizeRightBracket(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(']')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::RightBracket,
            ),
        );
    }

    #[Test]
    public function testTokenizeRightParenthesis(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(')')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::RightParenthesis,
            ),
        );
    }

    #[Test]
    public function testTokenizeSemicolon(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(';')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 1,
                ),
                type: TokenType::Semicolon,
            ),
        );
    }

    #[Test]
    public function testTokenizeShiftLeft(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<<')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::ShiftLeft,
            ),
        );
    }

    #[Test]
    public function testTokenizeShiftRight(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('>>')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 2,
                ),
                type: TokenType::ShiftRight,
            ),
        );
    }

    #[Test]
    public function testTokenizeSpaceship(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('<=>')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::Spaceship,
            ),
        );
    }

    #[Test]
    public function testTokenizeString(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer('"Hello, World!"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 15,
                ),
                type: TokenType::String,
                value: 'Hello, World!',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\'"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 3,
                ),
                type: TokenType::String,
                value: "'",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\""')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: '"',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\\\\"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: '\\',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\0"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\0",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\f"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\f",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\n"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\n",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\r"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\r",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\t"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\t",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\u1F600"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 9,
                ),
                type: TokenType::String,
                value: '😀',
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\v"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: "\v",
            ),
        );

        $this->assertEquals(
            actual: self::createTokenizer('"\p"')->next(),
            expected: new Token(
                location: new Location(
                    startLine: 1,
                    startOffset: 0,
                    endLine: 1,
                    endOffset: 4,
                ),
                type: TokenType::String,
                value: '\p',
            ),
        );
    }

    #[Test]
    public function testTokenizeWhitespace(): void
    {
        $this->assertEquals(
            actual: self::createTokenizer(" \t\nfoo")->next(),
            expected: new Token(
                location: new Location(
                    startLine: 2,
                    startOffset: 3,
                    endLine: 2,
                    endOffset: 6,
                ),
                type: TokenType::Identifier,
                value: 'foo',
            ),
        );
    }

    private static function createTokenizer(string $input): Tokenizer
    {
        return new Tokenizer($input);
    }
}
