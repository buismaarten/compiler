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

        $character = $this->input[$this->position];

        if (ctype_digit($character)) {
            return $this->tokenizeNumber();
        }

        if ($character === '+') {
            $this->position++;
            return new Token(TokenType::T_PLUS);
        }

        if ($character === '*') {
            $this->position++;
            return new Token(TokenType::T_MULTIPLY);
        }

        throw new Exception("Unexpected character: {$character}");
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

    /** @return Token[] */
    public function getTokens(): iterable
    {
        while (($token = $this->next()) !== null) {
            yield $token;
        }
    }
}
