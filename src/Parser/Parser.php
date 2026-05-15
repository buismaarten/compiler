<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Parser;

use Buismaarten\Parser\Exceptions\ExpectedTokenException;
use Buismaarten\Parser\Exceptions\UnexpectedEndOfInputException;
use Buismaarten\Parser\Lexer\LexerInterface;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;

final class Parser implements ParserInterface
{
    private LexerInterface $lexer;
    private ?Token $current = null;

    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
        $this->advance();
    }

    public function parse(): int
    {
        $value = $this->parseTerm();

        while ($this->current?->type === TokenType::T_PLUS) {
            $this->expect(TokenType::T_PLUS);
            $value += $this->parseTerm();
        }

        return $value;
    }

    private function parseTerm(): int
    {
        $value = $this->parseFactor();

        while ($this->current?->type === TokenType::T_MULTIPLY) {
            $this->expect(TokenType::T_MULTIPLY);
            $value *= $this->parseFactor();
        }

        return $value;
    }

    private function parseFactor(): int
    {
        $token = $this->expect(TokenType::T_NUMBER);

        return intval($token->value);
    }

    private function advance(): void
    {
        $this->current = $this->lexer->next();
    }

    private function expect(TokenType $type): Token
    {
        $token = $this->current;

        if ($token === null) {
            throw new UnexpectedEndOfInputException($type->name);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->advance();

        return $token;
    }
}
