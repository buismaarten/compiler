<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

class Parser implements ParserInterface
{
    private LexerInterface $lexer;
    private ?Token $current = null;

    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
        $this->advance();
    }

    private function advance(): void
    {
        $this->current = $this->lexer->next();
    }

    private function eat(TokenType $type): Token
    {
        $token = $this->current;

        if (!$token || $token->type !== $type) {
            throw new Exception("Expected $type");
        }

        $this->advance();
        return $token;
    }

    public function parse(): int
    {
        return $this->expr();
    }

    // expr := term (("+" term)*)
    private function expr(): int
    {
        $value = $this->term();

        while ($this->current && $this->current->type === TokenType::PLUS) {
            $this->eat(TokenType::PLUS);
            $value += $this->term();
        }

        return $value;
    }

    // term := factor (("*" factor)*)
    private function term(): int
    {
        $value = $this->factor();

        while ($this->current && $this->current->type === TokenType::MUL) {
            $this->eat(TokenType::MUL);
            $value *= $this->factor();
        }

        return $value;
    }

    // factor := NUMBER
    private function factor(): int
    {
        $token = $this->eat(TokenType::NUMBER);
        return (int)$token->value;
    }
}
