<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

use Override;
use Package\Exceptions\Tokenizer\UnexpectedCharacterException;
use Package\Exceptions\UnexpectedEndOfInputException;
use Package\Input\InputInterface;
use Package\Input\Input;

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
        while (($current = $this->input->current()) !== null && ctype_space($current)) {
            $this->input->next();

            if ($current === "\n") {
                $this->line++;
            }
        }

        if ($current === null) {
            return new Token(TokenType::Eof, $this->line);
        }

        $token = match ($current) {
            '*' => $this->tokenizeAsterisk(),
            '=' => $this->tokenizeCharacter(TokenType::Assign),
            ':' => $this->tokenizeCharacter(TokenType::Colon),
            ',' => $this->tokenizeCharacter(TokenType::Comma),
            '!' => $this->tokenizeCharacter(TokenType::ExclamationMark),
            '{' => $this->tokenizeCharacter(TokenType::LeftBrace),
            '[' => $this->tokenizeCharacter(TokenType::LeftBracket),
            '(' => $this->tokenizeCharacter(TokenType::LeftParenthesis),
            '?' => $this->tokenizeCharacter(TokenType::QuestionMark),
            '}' => $this->tokenizeCharacter(TokenType::RightBrace),
            ']' => $this->tokenizeCharacter(TokenType::RightBracket),
            ')' => $this->tokenizeCharacter(TokenType::RightParenthesis),
            ';' => $this->tokenizeCharacter(TokenType::Semicolon),
            '~' => $this->tokenizeCharacter(TokenType::Tilde),
            '_' => $this->tokenizeIdentifier(),
            '+' => $this->tokenizePlus(),
            '-' => $this->tokenizeMinus(),
            '/' => $this->tokenizeSlash(),
            '"' => $this->tokenizeString(),

            default => match (true) {
                ctype_alpha($current) => $this->tokenizeIdentifier(),
                ctype_digit($current) => $this->tokenizeNumber(),

                // Fallback
                default => throw new UnexpectedCharacterException($this->line, $current),
            },
        };

        if ($token === null) {
            return $this->next();
        }

        return $token;
    }

    private function tokenizeAsterisk(): Token
    {
        $this->input->next();

        if ($this->input->current() === '*') {
            $this->input->next();

            return new Token(TokenType::Exponent, $this->line);
        }

        return new Token(TokenType::Asterisk, $this->line);
    }

    private function tokenizeCharacter(TokenType $type): Token
    {
        $this->input->next();

        return new Token($type, $this->line);
    }

    private function tokenizeIdentifier(): Token
    {
        $start = $this->input->offset();

        while (($current = $this->input->current()) !== null && (ctype_alnum($current) || $current === '_')) {
            $this->input->next();
        }

        $value = $this->input->slice($start, $this->input->offset());
        $type = self::KEYWORDS[$value] ?? TokenType::Identifier;

        if ($type !== TokenType::Identifier) {
            $value = '';
        }

        return new Token($type, $this->line, $value);
    }

    private function tokenizeMinus(): Token
    {
        $this->input->next();

        if ($this->input->current() === '-') {
            $this->input->next();

            return new Token(TokenType::Decrement, $this->line);
        }

        return new Token(TokenType::Minus, $this->line);
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->input->offset();
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

        return new Token(
            type: $token,
            line: $this->line,
            value: $this->input->slice($start, $this->input->offset()),
        );
    }

    private function tokenizePlus(): Token
    {
        $this->input->next();

        if ($this->input->current() === '+') {
            $this->input->next();

            return new Token(TokenType::Increment, $this->line);
        }

        return new Token(TokenType::Plus, $this->line);
    }

    private function tokenizeSlash(): ?Token
    {
        $this->input->next();

        if ($this->input->current() === '/') {
            $this->input->next();

            while (($current = $this->input->current()) !== null && $current !== "\n") {
                $this->input->next();
            }

            return null;
        }

        return new Token(TokenType::Slash, $this->line);
    }

    private function tokenizeString(): Token
    {
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

        return new Token(TokenType::String, $this->line, $value);
    }
}
