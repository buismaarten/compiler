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
    private int $pos = 0;
    private int $length;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->length = strlen($input);
    }

    public function next(): ?Token
    {
        $this->skipWhitespace();

        if ($this->pos >= $this->length) {
            return null;
        }

        $char = $this->input[$this->pos];

        // number
        if (ctype_digit($char)) {
            return $this->number();
        }

        // operators
        if ($char === '+') {
            $this->pos++;
            return new Token(TokenType::PLUS, '+');
        }

        if ($char === '*') {
            $this->pos++;
            return new Token(TokenType::MUL, '*');
        }

        throw new Exception("Unexpected character: $char");
    }

    private function number(): Token
    {
        $start = $this->pos;

        while ($this->pos < $this->length && ctype_digit($this->input[$this->pos])) {
            $this->pos++;
        }

        $value = substr($this->input, $start, $this->pos - $start);

        return new Token(TokenType::NUMBER, $value);
    }

    private function skipWhitespace(): void
    {
        while ($this->pos < $this->length && ctype_space($this->input[$this->pos])) {
            $this->pos++;
        }
    }
}
