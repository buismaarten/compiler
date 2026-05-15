<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Lexer;

use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Exception;

final class Lexer implements LexerInterface
{
    private string $input;
    private int $length = 0;
    private int $offset = 0;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->length = strlen($input);
    }

    public function next(): ?Token
    {
        $this->skipWhitespace();

        if (!$this->valid()) {
            return null;
        }

        $current = $this->current();

        if (ctype_digit($current)) {
            return $this->tokenizeNumber();
        }

        if ($current === '+') {
            $this->offset++;
            return new Token(TokenType::T_PLUS);
        }

        if ($current === '*') {
            $this->offset++;
            return new Token(TokenType::T_MULTIPLY);
        }

        throw new Exception("Unexpected character: {$current}");
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->offset;

        while ($this->valid() && ctype_digit($this->current())) {
            $this->offset++;
        }

        $value = substr($this->input, $start, $this->offset - $start);

        return new Token(TokenType::T_NUMBER, $value);
    }

    private function skipWhitespace(): void
    {
        while ($this->valid() && ctype_space($this->current())) {
            $this->offset++;
        }
    }

    private function current(): string
    {
        return $this->input[$this->offset];
    }

    private function valid(): bool
    {
        return $this->offset < $this->length;
    }

    /** @return Token[] */
    public function getTokens(): iterable
    {
        while (($token = $this->next()) !== null) {
            yield $token;
        }
    }
}
