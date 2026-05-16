<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Lexer;

use Buismaarten\Parser\Exceptions\Lexer\UnexpectedCharacterException;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Override;

final class Lexer implements LexerInterface
{
    private string $input;
    private int $offset = 0;
    private int $line = 1;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    #[Override]
    public function next(): Token
    {
        while (($current = $this->current()) !== null && ctype_space($current)) {
            $this->offset++;

            if ($current === "\n") {
                $this->line++;
            }
        }

        if (($current = $this->current()) === null) {
            return new Token(TokenType::T_EOF, $this->line);
        }

        return match ($current) {
            '+' => $this->tokenizeSingle(TokenType::T_PLUS),
            '*' => $this->tokenizeSingle(TokenType::T_MULTIPLY),

            default => match (true) {
                ctype_digit($current) => $this->tokenizeNumber(),

                // Fallback
                default => throw new UnexpectedCharacterException($this->line, $current),
            },
        };
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->offset;

        while (($current = $this->current()) !== null && ctype_digit($current)) {
            $this->offset++;
        }

        $value = substr($this->input, $start, $this->offset - $start);

        return new Token(TokenType::T_NUMBER, $this->line, $value);
    }

    private function tokenizeSingle(TokenType $type): Token
    {
        $this->offset++;

        return new Token($type, $this->line);
    }

    private function current(): ?string
    {
        return $this->input[$this->offset] ?? null;
    }

    #[Override]
    public function getTokens(): iterable
    {
        while (($token = $this->next())->type !== TokenType::T_EOF) {
            yield $token;
        }
    }
}
