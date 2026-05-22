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

    private function parseBooleanLiteral(): Literals\BooleanLiteral
    {
        $token = $this->current;

        if ($token->type === TokenType::T_KEYWORD_TRUE) {
            $this->expect(TokenType::T_KEYWORD_TRUE);

            return new Literals\BooleanLiteral(true);
        }

        if ($token->type === TokenType::T_KEYWORD_FALSE) {
            $this->expect(TokenType::T_KEYWORD_FALSE);

            return new Literals\BooleanLiteral(false);
        }

        throw new UnexpectedTokenException($token->line, $token->type->name);
    }

    private function parseBreakStatement(): Statements\BreakStatement
    {
        $this->expect(TokenType::T_KEYWORD_BREAK);

        return new Statements\BreakStatement;
    }

    private function parseContinueStatement(): Statements\ContinueStatement
    {
        $this->expect(TokenType::T_KEYWORD_CONTINUE);

        return new Statements\ContinueStatement;
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::T_KEYWORD_FALSE => $this->parseBooleanLiteral(),
            TokenType::T_KEYWORD_TRUE  => $this->parseBooleanLiteral(),
            TokenType::T_IDENTIFIER    => $this->parseIdentifierExpression(),
            TokenType::T_INTEGER       => $this->parseIntegerLiteral(),
            TokenType::T_KEYWORD_NULL  => $this->parseNullLiteral(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parseExpressionStatement(): Statements\ExpressionStatement
    {
        $expression = $this->parseExpression();

        return new Statements\ExpressionStatement($expression);
    }

    private function parseIdentifierExpression(): Expressions\IdentifierExpression
    {
        $token = $this->expect(TokenType::T_IDENTIFIER);

        return new Expressions\IdentifierExpression($token->value);
    }

    private function parseIntegerLiteral(): Literals\IntegerLiteral
    {
        $token = $this->expect(TokenType::T_INTEGER);
        $value = intval($token->value);

        return new Literals\IntegerLiteral($value);
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $this->expect(TokenType::T_KEYWORD_NULL);

        return new Literals\NullLiteral;
    }

    private function parseStatement(): AbstractStatement
    {
        $statement = match ($this->current->type) {
            TokenType::T_KEYWORD_BREAK    => $this->parseBreakStatement(),
            TokenType::T_KEYWORD_CONTINUE => $this->parseContinueStatement(),

            // TODO
            // TokenType::T_KEYWORD_WHILE    => $this->parseWhileStatement(),

            // Fallback
            default => $this->parseExpressionStatement(),
        };

        $this->expect(TokenType::T_SEMICOLON);

        return $statement;
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
