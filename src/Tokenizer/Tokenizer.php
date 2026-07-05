<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

use Override;
use Package\Exceptions\Tokenizer\UnexpectedCharacterException;
use Package\Exceptions\Tokenizer\UnexpectedEndOfInputException;
use Package\Input\Input;
use Package\Input\InputInterface;

final class Tokenizer implements TokenizerInterface
{
    private const KEYWORDS = [
        'break'    => TokenType::KeywordBreak,
        'const'    => TokenType::KeywordConst,
        'continue' => TokenType::KeywordContinue,
        'else'     => TokenType::KeywordElse,
        'false'    => TokenType::KeywordFalse,
        'fn'       => TokenType::KeywordFn,
        'if'       => TokenType::KeywordIf,
        'null'     => TokenType::KeywordNull,
        'print'    => TokenType::KeywordPrint,
        'return'   => TokenType::KeywordReturn,
        'true'     => TokenType::KeywordTrue,
        'var'      => TokenType::KeywordVar,
        'while'    => TokenType::KeywordWhile,
    ];

    private InputInterface $input;
    private int $line = 1;

    public function __construct(InputInterface|string $input)
    {
        if (is_string($input)) {
            $input = new Input($input);
        }

        $this->input = $input;
    }

    #[Override]
    public function next(): Token
    {
        while (true) {
            $offset = $this->input->offset();

            while (($current = $this->input->current()) !== null && ctype_space($current)) {
                $this->input->next();

                if ($current === "\n") {
                    $this->line++;
                }
            }

            if ($current === null) {
                return $this->createToken(TokenType::Eof, $offset);
            }

            $token = match ($current) {
                '&' => $this->tokenizeAmpersand(),
                '=' => $this->tokenizeAssign(),
                '*' => $this->tokenizeAsterisk(),
                '~' => $this->tokenizeCharacter(TokenType::BitwiseNot),
                '^' => $this->tokenizeCharacter(TokenType::BitwiseXor),
                ':' => $this->tokenizeCharacter(TokenType::Colon),
                ',' => $this->tokenizeCharacter(TokenType::Comma),
                '{' => $this->tokenizeCharacter(TokenType::LeftBrace),
                '[' => $this->tokenizeCharacter(TokenType::LeftBracket),
                '(' => $this->tokenizeCharacter(TokenType::LeftParenthesis),
                '%' => $this->tokenizeCharacter(TokenType::Modulo),
                '?' => $this->tokenizeCharacter(TokenType::QuestionMark),
                '}' => $this->tokenizeCharacter(TokenType::RightBrace),
                ']' => $this->tokenizeCharacter(TokenType::RightBracket),
                ')' => $this->tokenizeCharacter(TokenType::RightParenthesis),
                ';' => $this->tokenizeCharacter(TokenType::Semicolon),
                '!' => $this->tokenizeExclamationMark(),
                '_' => $this->tokenizeIdentifier(),
                '<' => $this->tokenizeLeftAngle(),
                '-' => $this->tokenizeMinus(),
                '|' => $this->tokenizePipe(),
                '+' => $this->tokenizePlus(),
                '>' => $this->tokenizeRightAngle(),
                '/' => $this->tokenizeSlash(),
                '"' => $this->tokenizeString(),

                default => match (true) {
                    ctype_alpha($current) => $this->tokenizeIdentifier(),
                    ctype_digit($current) => $this->tokenizeNumber(),

                    // Fallback
                    default => throw new UnexpectedCharacterException($this->line, $current),
                },
            };

            if ($token !== null) {
                return $token;
            }
        }
    }

    private function tokenizeAmpersand(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '&') {
            $this->input->next();

            return $this->createToken(TokenType::BooleanAnd, $offset);
        }

        return $this->createToken(TokenType::BitwiseAnd, $offset);
    }

    private function tokenizeAssign(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '=') {
            $this->input->next();

            if ($this->input->current() === '=') {
                $this->input->next();

                return $this->createToken(TokenType::Identical, $offset);
            }

            return $this->createToken(TokenType::Equal, $offset);
        }

        return $this->createToken(TokenType::Assign, $offset);
    }

    private function tokenizeAsterisk(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        return $this->createToken(TokenType::Multiply, $offset);
    }

    private function tokenizeCharacter(TokenType $type): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        return $this->createToken($type, $offset);
    }

    private function tokenizeExclamationMark(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '=') {
            $this->input->next();

            if ($this->input->current() === '=') {
                $this->input->next();

                return $this->createToken(TokenType::NotIdentical, $offset);
            }

            return $this->createToken(TokenType::NotEqual, $offset);
        }

        return $this->createToken(TokenType::BooleanNot, $offset);
    }

    private function tokenizeIdentifier(): Token
    {
        $offset = $this->input->offset();

        while (($current = $this->input->current()) !== null && (ctype_alnum($current) || $current === '_')) {
            $this->input->next();
        }

        $value = $this->input->slice($offset, $this->input->offset());
        $type = self::KEYWORDS[$value] ?? TokenType::Identifier;

        if ($type !== TokenType::Identifier) {
            $value = '';
        }

        return $this->createToken($type, $offset, $value);
    }

    private function tokenizeLeftAngle(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '<') {
            $this->input->next();

            return $this->createToken(TokenType::ShiftLeft, $offset);
        }

        if ($this->input->current() === '=') {
            $this->input->next();

            if ($this->input->current() === '>') {
                $this->input->next();

                return $this->createToken(TokenType::Spaceship, $offset);
            }

            return $this->createToken(TokenType::LessThanEqual, $offset);
        }

        return $this->createToken(TokenType::LessThan, $offset);
    }

    private function tokenizeMinus(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '-') {
            $this->input->next();

            return $this->createToken(TokenType::Decrement, $offset);
        }

        return $this->createToken(TokenType::Minus, $offset);
    }

    private function tokenizeNumber(): Token
    {
        $offset = $this->input->offset();
        $token = TokenType::Integer;

        while (($current = $this->input->current()) !== null && ctype_digit($current)) {
            $this->input->next();
        }

        if ($current === '.') {
            $token = TokenType::Float;
            $this->input->next();

            if (($current = $this->input->current()) === null || !ctype_digit($current)) {
                throw new UnexpectedEndOfInputException($this->line);
            }

            while (($current = $this->input->current()) !== null && ctype_digit($current)) {
                $this->input->next();
            }
        }

        return $this->createToken($token, $offset, $this->input->slice($offset, $this->input->offset()));
    }

    private function tokenizePipe(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '|') {
            $this->input->next();

            return $this->createToken(TokenType::BooleanOr, $offset);
        }

        return $this->createToken(TokenType::BitwiseOr, $offset);
    }

    private function tokenizePlus(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '+') {
            $this->input->next();

            return $this->createToken(TokenType::Increment, $offset);
        }

        return $this->createToken(TokenType::Plus, $offset);
    }

    private function tokenizeRightAngle(): Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '>') {
            $this->input->next();

            return $this->createToken(TokenType::ShiftRight, $offset);
        }

        if ($this->input->current() === '=') {
            $this->input->next();

            return $this->createToken(TokenType::GreaterThanEqual, $offset);
        }

        return $this->createToken(TokenType::GreaterThan, $offset);
    }

    private function tokenizeSlash(): ?Token
    {
        $offset = $this->input->offset();
        $this->input->next();

        if ($this->input->current() === '/') {
            $this->input->next();

            while (($current = $this->input->current()) !== null && $current !== "\n") {
                $this->input->next();
            }

            return null;
        }

        return $this->createToken(TokenType::Divide, $offset);
    }

    private function tokenizeString(): Token
    {
        $offset = $this->input->offset();
        $value = '';

        if ($this->input->current() === '"') {
            $this->input->next();
        }

        while (($current = $this->input->current()) !== null && $current !== '"') {
            if ($current === '\\') {
                $this->input->next();

                if (($current = $this->input->current()) !== null) {
                    switch ($current) {
                        case 'u':
                            $hex = '';
                            $this->input->next();

                            while (($current = $this->input->current()) !== null && ctype_xdigit($current)) {
                                $hex .= $current;
                                $this->input->next();
                            }

                            $value .= mb_chr(intval($hex, 16), 'UTF-8');
                        break;

                        default:
                            $value .= match ($current) {
                                "'"  => "'",
                                '"'  => '"',
                                '\\' => '\\',
                                '0'  => "\0",
                                'f'  => "\f",
                                'n'  => "\n",
                                'r'  => "\r",
                                't'  => "\t",
                                'v'  => "\v",

                                // Fallback
                                default => '\\'.$current,
                            };

                            $this->input->next();
                        break;
                    }
                }
            } else {
                $value .= $current;
                $this->input->next();
            }

            if ($current === null) {
                throw new UnexpectedEndOfInputException($this->line);
            }
        }

        if ($this->input->current() === '"') {
            $this->input->next();
        }

        return $this->createToken(TokenType::String, $offset, $value);
    }

    private function createToken(TokenType $type, int $startOffset, string $value = ''): Token
    {
        return new Token(
            type: $type,
            line: $this->line,
            startOffset: $startOffset,
            endOffset: $this->input->offset(),
            value: $value,
        );
    }
}
