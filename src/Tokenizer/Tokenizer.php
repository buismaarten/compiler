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
        'break'    => TokenType::T_KEYWORD_BREAK,
        'const'    => TokenType::T_KEYWORD_CONST,
        'continue' => TokenType::T_KEYWORD_CONTINUE,
        'else'     => TokenType::T_KEYWORD_ELSE,
        'false'    => TokenType::T_KEYWORD_FALSE,
        'fn'       => TokenType::T_KEYWORD_FN,
        'if'       => TokenType::T_KEYWORD_IF,
        'null'     => TokenType::T_KEYWORD_NULL,
        'print'    => TokenType::T_KEYWORD_PRINT,
        'return'   => TokenType::T_KEYWORD_RETURN,
        'true'     => TokenType::T_KEYWORD_TRUE,
        'var'      => TokenType::T_KEYWORD_VAR,
        'while'    => TokenType::T_KEYWORD_WHILE,
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
            return new Token(TokenType::T_EOF, $this->line);
        }

        $token = match ($current) {
            '=' => $this->tokenizeCharacter(TokenType::T_ASSIGN),
            '*' => $this->tokenizeCharacter(TokenType::T_ASTERISK),
            ':' => $this->tokenizeCharacter(TokenType::T_COLON),
            ',' => $this->tokenizeCharacter(TokenType::T_COMMA),
            '!' => $this->tokenizeCharacter(TokenType::T_EXCLAMATION_MARK),
            '{' => $this->tokenizeCharacter(TokenType::T_LEFT_BRACE),
            '[' => $this->tokenizeCharacter(TokenType::T_LEFT_BRACKET),
            '(' => $this->tokenizeCharacter(TokenType::T_LEFT_PARENTHESIS),
            '?' => $this->tokenizeCharacter(TokenType::T_QUESTION_MARK),
            '}' => $this->tokenizeCharacter(TokenType::T_RIGHT_BRACE),
            ']' => $this->tokenizeCharacter(TokenType::T_RIGHT_BRACKET),
            ')' => $this->tokenizeCharacter(TokenType::T_RIGHT_PARENTHESIS),
            ';' => $this->tokenizeCharacter(TokenType::T_SEMICOLON),
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
        $type = self::KEYWORDS[$value] ?? TokenType::T_IDENTIFIER;

        if ($type !== TokenType::T_IDENTIFIER) {
            $value = '';
        }

        return new Token($type, $this->line, $value);
    }

    private function tokenizeMinus(): Token
    {
        $this->input->next();

        if ($this->input->current() === '-') {
            $this->input->next();

            return new Token(TokenType::T_DECREMENT, $this->line);
        }

        return new Token(TokenType::T_MINUS, $this->line);
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->input->offset();
        $token = TokenType::T_INTEGER;

        while (($current = $this->input->current()) !== null && ctype_digit($current)) {
            $this->input->next();
        }

        if ($current === '.') {
            $token = TokenType::T_FLOAT;
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

            return new Token(TokenType::T_INCREMENT, $this->line);
        }

        return new Token(TokenType::T_PLUS, $this->line);
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

        return new Token(TokenType::T_SLASH, $this->line);
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

        return new Token(TokenType::T_STRING, $this->line, $value);
    }
}
