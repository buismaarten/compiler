<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Tokenizer;

use Package\Core\Location;
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
    public function testBitwiseAnd(): void
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
    public function testBitwiseNot(): void
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
    public function testBitwiseOr(): void
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
    public function testBitwiseXor(): void
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
    public function testBooleanAnd(): void
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
    public function testBooleanNot(): void
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
    public function testBooleanOr(): void
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
    public function testColon(): void
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
    public function testComma(): void
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
    public function testComment(): void
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
    public function testDecrement(): void
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
    public function testDivide(): void
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
    public function testEqual(): void
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
    public function testFloat(): void
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
    public function testGreaterThan(): void
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
    public function testGreaterThanEqual(): void
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
    public function testIdentical(): void
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
    public function testIdentifier(): void
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
    public function testIncrement(): void
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
    public function testInteger(): void
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
    public function testKeyword(): void
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
    public function testLeftBrace(): void
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
    public function testLeftBracket(): void
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
    public function testLeftParenthesis(): void
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
    public function testLessThan(): void
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
    public function testLessThanEqual(): void
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
    public function testMinus(): void
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
    public function testModulo(): void
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
    public function testMultiply(): void
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
    public function testNotEqual(): void
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
    public function testNotIdentical(): void
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
    public function testPlus(): void
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
    public function testQuestionMark(): void
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
    public function testRightBrace(): void
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
    public function testRightBracket(): void
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
    public function testRightParenthesis(): void
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
    public function testSemicolon(): void
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
    public function testShiftLeft(): void
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
    public function testShiftRight(): void
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
    public function testSpaceship(): void
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
    public function testString(): void
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
    public function testWhitespace(): void
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

    private static function createTokenizer(string $input): TokenizerInterface
    {
        return new Tokenizer($input);
    }
}
