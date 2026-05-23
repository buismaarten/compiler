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

    private function parseBooleanLiteral(bool $value): Literals\BooleanLiteral
    {
        if ($value) {
            $this->expect(TokenType::T_KEYWORD_TRUE);
        } else {
            $this->expect(TokenType::T_KEYWORD_FALSE);
        }

        return new Literals\BooleanLiteral($value);
    }

    private function parseBreakStatement(): Statements\BreakStatement
    {
        $this->expect(TokenType::T_KEYWORD_BREAK);
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\BreakStatement;
    }

    private function parseConstStatement(): Statements\ConstStatement
    {
        $this->expect(TokenType::T_KEYWORD_CONST);

        $identifier = $this->expect(TokenType::T_IDENTIFIER);
        $this->expect(TokenType::T_ASSIGN);

        $value = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ConstStatement($identifier->value, $value);
    }

    private function parseContinueStatement(): Statements\ContinueStatement
    {
        $this->expect(TokenType::T_KEYWORD_CONTINUE);
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ContinueStatement;
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::T_IDENTIFIER    => $this->parseIdentifierExpression(),
            TokenType::T_INTEGER       => $this->parseIntegerLiteral(),
            TokenType::T_KEYWORD_FALSE => $this->parseBooleanLiteral(false),
            TokenType::T_KEYWORD_NULL  => $this->parseNullLiteral(),
            TokenType::T_KEYWORD_TRUE  => $this->parseBooleanLiteral(true),

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

    private function parsePrintStatement(): Statements\PrintStatement
    {
        $this->expect(TokenType::T_KEYWORD_PRINT);

        $expression = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\PrintStatement($expression);
    }

    private function parseReturnStatement(): Statements\ReturnStatement
    {
        $this->expect(TokenType::T_KEYWORD_RETURN);

        if ($this->current->type !== TokenType::T_SEMICOLON) {
            $value = $this->parseExpression();
        } else {
            $value = null;
        }

        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ReturnStatement($value);
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            TokenType::T_KEYWORD_BREAK    => $this->parseBreakStatement(),
            TokenType::T_KEYWORD_CONST    => $this->parseConstStatement(),
            TokenType::T_KEYWORD_CONTINUE => $this->parseContinueStatement(),
            TokenType::T_KEYWORD_PRINT    => $this->parsePrintStatement(),
            TokenType::T_KEYWORD_RETURN   => $this->parseReturnStatement(),
            TokenType::T_KEYWORD_VAR      => $this->parseVarStatement(),
            TokenType::T_KEYWORD_WHILE    => $this->parseWhileStatement(),

            // Fallback
            default => $this->parseExpressionStatement(),
        };
    }

    private function parseVarStatement(): Statements\VarStatement
    {
        $this->expect(TokenType::T_KEYWORD_VAR);

        $identifier = $this->expect(TokenType::T_IDENTIFIER);
        $this->expect(TokenType::T_ASSIGN);

        $value = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\VarStatement($identifier->value, $value);
    }

    private function parseWhileStatement(): Statements\WhileStatement
    {
        $this->expect(TokenType::T_KEYWORD_WHILE);
        $this->expect(TokenType::T_LEFT_PARENTHESIS);

        $condition = $this->parseExpression();
        $statements = [];

        $this->expect(TokenType::T_RIGHT_PARENTHESIS);
        $this->expect(TokenType::T_LEFT_BRACE);

        while ($this->current->type !== TokenType::T_RIGHT_BRACE) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::T_RIGHT_BRACE);

        return new Statements\WhileStatement($condition, $statements);
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
