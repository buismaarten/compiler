<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

require_once __DIR__.'/src/Token/TokenType.php';
require_once __DIR__.'/src/Token/Token.php';
require_once __DIR__.'/src/Lexer/LexerInterface.php';
require_once __DIR__.'/src/Lexer/Lexer.php';

class Parser
{
    private Lexer $lexer;
    private ?Token $current = null;

    public function __construct(Lexer $lexer)
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

$lexer = new Lexer("2 + 3 * 4");
$parser = new Parser($lexer);

$result = $parser->parse();

echo $result; // 14
