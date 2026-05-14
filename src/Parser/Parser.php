<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Parser;

use Buismaarten\Parser\Lexer\LexerInterface;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Exception;

final class Parser implements ParserInterface
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
        $value = $this->parseTerm();

        while ($this->current && $this->current->type === TokenType::T_PLUS) {
            $this->eat(TokenType::T_PLUS);
            $value += $this->parseTerm();
        }

        return $value;
    }

    private function parseTerm(): int
    {
        $value = $this->parseFactor();

        while ($this->current && $this->current->type === TokenType::T_MULTIPLY) {
            $this->eat(TokenType::T_MULTIPLY);
            $value *= $this->parseFactor();
        }

        return $value;
    }

    private function parseFactor(): int
    {
        $token = $this->eat(TokenType::T_NUMBER);

        return intval($token->value);
    }
}
