<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

use Override;
use Package\Exceptions\Tokenizer\UnexpectedCharacterException;
use Package\Exceptions\UnexpectedEndOfInputException;
use Package\Input\Input;
use Package\Input\InputInterface;

final class Tokenizer implements TokenizerInterface
{
    private const KEYWORDS = [
        'break'    => TokenType::KEYWORD_BREAK,
        'const'    => TokenType::KEYWORD_CONST,
        'continue' => TokenType::KEYWORD_CONTINUE,
        'else'     => TokenType::KEYWORD_ELSE,
        'false'    => TokenType::KEYWORD_FALSE,
        'fn'       => TokenType::KEYWORD_FN,
        'if'       => TokenType::KEYWORD_IF,
        'null'     => TokenType::KEYWORD_NULL,
        'print'    => TokenType::KEYWORD_PRINT,
        'return'   => TokenType::KEYWORD_RETURN,
        'true'     => TokenType::KEYWORD_TRUE,
        'var'      => TokenType::KEYWORD_VAR,
        'while'    => TokenType::KEYWORD_WHILE,
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
            return new Token(TokenType::EOF, $this->line);
        }

        $token = match ($current) {
            '=' => $this->tokenizeCharacter(TokenType::ASSIGN),
            '*' => $this->tokenizeCharacter(TokenType::ASTERISK),
            ':' => $this->tokenizeCharacter(TokenType::COLON),
            ',' => $this->tokenizeCharacter(TokenType::COMMA),
            '!' => $this->tokenizeCharacter(TokenType::EXCLAMATION_MARK),
            '{' => $this->tokenizeCharacter(TokenType::LEFT_BRACE),
            '[' => $this->tokenizeCharacter(TokenType::LEFT_BRACKET),
            '(' => $this->tokenizeCharacter(TokenType::LEFT_PARENTHESIS),
            '?' => $this->tokenizeCharacter(TokenType::QUESTION_MARK),
            '}' => $this->tokenizeCharacter(TokenType::RIGHT_BRACE),
            ']' => $this->tokenizeCharacter(TokenType::RIGHT_BRACKET),
            ')' => $this->tokenizeCharacter(TokenType::RIGHT_PARENTHESIS),
            ';' => $this->tokenizeCharacter(TokenType::SEMICOLON),
            '~' => $this->tokenizeCharacter(TokenType::TILDE),
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
        $type = self::KEYWORDS[$value] ?? TokenType::IDENTIFIER;

        if ($type !== TokenType::IDENTIFIER) {
            $value = '';
        }

        return new Token($type, $this->line, $value);
    }

    private function tokenizeMinus(): Token
    {
        $this->input->next();

        if ($this->input->current() === '-') {
            $this->input->next();

            return new Token(TokenType::DECREMENT, $this->line);
        }

        return new Token(TokenType::MINUS, $this->line);
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->input->offset();
        $token = TokenType::INTEGER;

        while (($current = $this->input->current()) !== null && ctype_digit($current)) {
            $this->input->next();
        }

        if ($current === '.') {
            $token = TokenType::FLOAT;
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

            return new Token(TokenType::INCREMENT, $this->line);
        }

        return new Token(TokenType::PLUS, $this->line);
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

        return new Token(TokenType::SLASH, $this->line);
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

        return new Token(TokenType::STRING, $this->line, $value);
    }
}
