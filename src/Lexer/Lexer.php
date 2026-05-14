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
    private int $length;
    private int $position = 0;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->length = strlen($input);
    }

    public function next(): ?Token
    {
        $this->skipWhitespace();

        if ($this->position >= $this->length) {
            return null;
        }

        $char = $this->input[$this->position];

        if (ctype_digit($char)) {
            return $this->tokenizeNumber();
        }

        if ($char === '+') {
            $this->position++;
            return new Token(TokenType::T_PLUS, '+');
        }

        if ($char === '*') {
            $this->position++;
            return new Token(TokenType::T_MULTIPLY, '*');
        }

        throw new Exception("Unexpected character: $char");
    }

    public function getTokens(): array
    {
        $tokens = [];

        while ($token = $this->next()) {
            $tokens[] = $token;
        }

        $this->position = 0;

        return $tokens;
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->position;

        while ($this->position < $this->length && ctype_digit($this->input[$this->position])) {
            $this->position++;
        }

        $value = substr($this->input, $start, $this->position - $start);

        return new Token(TokenType::T_NUMBER, $value);
    }

    private function skipWhitespace(): void
    {
        while ($this->position < $this->length && ctype_space($this->input[$this->position])) {
            $this->position++;
        }
    }
}
