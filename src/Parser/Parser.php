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
use Package\Nodes\AbstractStatement;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Statements;
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
    public function parse(): array
    {
        $statements = [];

        while ($this->current->type !== TokenType::T_EOF) {
            $statements[] = $this->parseStatement();
        }

        return $statements;
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::T_KEYWORD_FALSE => $this->parseBooleanLiteral(),
            TokenType::T_KEYWORD_TRUE  => $this->parseBooleanLiteral(),
            TokenType::T_IDENTIFIER    => $this->parseIdentifierExpression(),
            TokenType::T_KEYWORD_NULL  => $this->parseNullLiteral(),
            TokenType::T_NUMBER        => $this->parseNumberExpression(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parseExpressionStatement(): Statements\ExpressionStatement
    {
        $expression = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ExpressionStatement($expression);
    }

    private function parseIdentifierExpression(): Expressions\IdentifierNode
    {
        $token = $this->expect(TokenType::T_IDENTIFIER);

        return new Expressions\IdentifierNode($token->value);
    }

    private function parseNumberExpression(): Expressions\NumberNode
    {
        $token = $this->expect(TokenType::T_NUMBER);
        $value = intval($token->value);

        return new Expressions\NumberNode($value);
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            // TODO

            default => $this->parseExpressionStatement(),
        };
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
