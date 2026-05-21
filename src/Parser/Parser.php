<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Parser;

use Override;
use Package\Exceptions\Parser\ExpectedTokenException;
use Package\Exceptions\Parser\UnexpectedEndOfInputException;
use Package\Exceptions\Parser\UnexpectedTokenException;
use Package\Lexer\LexerInterface;
use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions\BinaryNode;
use Package\Nodes\Expressions\IdentifierNode;
use Package\Nodes\Expressions\NumberNode;
use Package\Token\Token;
use Package\Token\TokenType;

final class Parser implements ParserInterface
{
    private LexerInterface $lexer;
    private Token $current;

    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
        $this->current = $this->lexer->next();
    }

    #[Override]
    public function parse(): AbstractNode
    {
        $node = $this->parseTerm();

        while ($this->current->type === TokenType::T_PLUS) {
            $operator = $this->expect(TokenType::T_PLUS);
            $node = new BinaryNode($operator->type->name, $node, $this->parseTerm());
        }

        return $node;
    }

    private function parseTerm(): AbstractExpression
    {
        $node = $this->parseFactor();

        while ($this->current->type === TokenType::T_MULTIPLY) {
            $operator = $this->expect(TokenType::T_MULTIPLY);
            $node = new BinaryNode($operator->type->name, $node, $this->parseFactor());
        }

        return $node;
    }

    private function parseFactor(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::T_NUMBER     => $this->parseNumber(),
            TokenType::T_IDENTIFIER => $this->parseIdentifier(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->value),
        };
    }

    private function parseNumber(): NumberNode
    {
        $token = $this->expect(TokenType::T_NUMBER);
        $value = intval($token->value);

        return new NumberNode($value);
    }

    private function parseIdentifier(): IdentifierNode
    {
        $token = $this->expect(TokenType::T_IDENTIFIER);

        return new IdentifierNode($token->value);
    }

    private function expect(TokenType $type): Token
    {
        $token = $this->current;

        if ($token->type === TokenType::T_EOF) {
            throw new UnexpectedEndOfInputException($token->line);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->current = $this->lexer->next();

        return $token;
    }
}
