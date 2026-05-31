<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Parser;

use Override;
use Package\Exceptions\Parser\ExpectedTokenException;
use Package\Exceptions\Parser\UnexpectedTokenException;
use Package\Exceptions\UnexpectedEndOfInputException;
use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Statements;
use Package\Tokenizer\Token;
use Package\Tokenizer\TokenizerInterface;
use Package\Tokenizer\TokenType;

final class Parser implements ParserInterface
{
    private TokenizerInterface $tokenizer;
    private Token $current;

    public function __construct(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->current = $this->tokenizer->next();
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
            $token = $this->expect(TokenType::T_KEYWORD_TRUE);
        } else {
            $token = $this->expect(TokenType::T_KEYWORD_FALSE);
        }

        return new Literals\BooleanLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseBreakStatement(): Statements\BreakStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_BREAK);

        if ($this->current->type === TokenType::T_INTEGER) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\BreakStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseConstStatement(): Statements\ConstStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_CONST);
        $name = $this->expect(TokenType::T_IDENTIFIER);
        $this->expect(TokenType::T_ASSIGN);

        $expression = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ConstStatement(
            line: $token->line,
            name: $name->value,
            expression: $expression,
        );
    }

    private function parseContinueStatement(): Statements\ContinueStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_CONTINUE);

        if ($this->current->type === TokenType::T_INTEGER) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ContinueStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::T_FLOAT         => $this->parseFloatLiteral(),
            TokenType::T_IDENTIFIER    => $this->parseIdentifierExpression(),
            TokenType::T_INTEGER       => $this->parseIntegerLiteral(),
            TokenType::T_KEYWORD_FALSE => $this->parseBooleanLiteral(false),
            TokenType::T_KEYWORD_NULL  => $this->parseNullLiteral(),
            TokenType::T_KEYWORD_TRUE  => $this->parseBooleanLiteral(true),
            TokenType::T_STRING        => $this->parseStringLiteral(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parseExpressionStatement(): Statements\ExpressionStatement
    {
        $token = $this->current;
        $expression = $this->parseExpression();

        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ExpressionStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseFloatLiteral(): Literals\FloatLiteral
    {
        $token = $this->expect(TokenType::T_FLOAT);
        $value = floatval($token->value);

        return new Literals\FloatLiteral(
            line: $token->line,
            value: $value,
        );
    }

    // TODO
    private function parseFunctionStatement(): Statements\FunctionStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_FN);
        $name = $this->expect(TokenType::T_IDENTIFIER);
        $statements = [];

        $this->expect(TokenType::T_LEFT_PARENTHESIS);
        $this->expect(TokenType::T_RIGHT_PARENTHESIS);
        $this->expect(TokenType::T_LEFT_BRACE);

        while ($this->current->type !== TokenType::T_RIGHT_BRACE) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::T_RIGHT_BRACE);

        return new Statements\FunctionStatement(
            line: $token->line,
            name: $name->value,
            statements: $statements,
        );
    }

    private function parseIdentifierExpression(): AbstractExpression
    {
        $token = $this->expect(TokenType::T_IDENTIFIER);

        if ($this->current->type === TokenType::T_ASSIGN) {
            $this->expect(TokenType::T_ASSIGN);

            return new Expressions\AssignmentExpression(
                line: $token->line,
                name: $token->value,
                expression: $this->parseExpression(),
            );
        }

        if ($this->current->type === TokenType::T_DECREMENT) {
            $this->expect(TokenType::T_DECREMENT);

            return new Expressions\DecrementExpression(
                line: $token->line,
                name: $token->value,
            );
        }

        if ($this->current->type === TokenType::T_INCREMENT) {
            $this->expect(TokenType::T_INCREMENT);

            return new Expressions\IncrementExpression(
                line: $token->line,
                name: $token->value,
            );
        }

        if ($this->current->type === TokenType::T_LEFT_PARENTHESIS) {
            $arguments = [];

            $this->expect(TokenType::T_LEFT_PARENTHESIS);

            while ($this->current->type !== TokenType::T_RIGHT_PARENTHESIS) {
                $arguments[] = $this->parseExpression();

                if ($this->current->type === TokenType::T_COMMA) {
                    $this->expect(TokenType::T_COMMA);
                } else {
                    break;
                }
            }

            $this->expect(TokenType::T_RIGHT_PARENTHESIS);

            return new Expressions\FunctionCallExpression(
                line: $token->line,
                name: $token->value,
                arguments: $arguments,
            );
        }

        return new Expressions\IdentifierExpression(
            line: $token->line,
            name: $token->value,
        );
    }

    private function parseIfStatement(): Statements\IfStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_IF);
        $this->expect(TokenType::T_LEFT_PARENTHESIS);

        $condition = $this->parseExpression();
        $thenStatements = [];
        $elseStatements = [];

        $this->expect(TokenType::T_RIGHT_PARENTHESIS);
        $this->expect(TokenType::T_LEFT_BRACE);

        while ($this->current->type !== TokenType::T_RIGHT_BRACE) {
            $thenStatements[] = $this->parseStatement();
        }

        $this->expect(TokenType::T_RIGHT_BRACE);

        if ($this->current->type === TokenType::T_KEYWORD_ELSE) {
            $this->expect(TokenType::T_KEYWORD_ELSE);
            $this->expect(TokenType::T_LEFT_BRACE);

            while ($this->current->type !== TokenType::T_RIGHT_BRACE) {
                $elseStatements[] = $this->parseStatement();
            }

            $this->expect(TokenType::T_RIGHT_BRACE);
        }

        return new Statements\IfStatement(
            line: $token->line,
            condition: $condition,
            thenStatements: $thenStatements,
            elseStatements: $elseStatements,
        );
    }

    private function parseIntegerLiteral(): Literals\IntegerLiteral
    {
        $token = $this->expect(TokenType::T_INTEGER);
        $value = intval($token->value);

        return new Literals\IntegerLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $token = $this->expect(TokenType::T_KEYWORD_NULL);

        return new Literals\NullLiteral(
            line: $token->line,
        );
    }

    private function parsePrintStatement(): Statements\PrintStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_PRINT);

        $expression = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\PrintStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseReturnStatement(): Statements\ReturnStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_RETURN);

        if ($this->current->type !== TokenType::T_SEMICOLON) {
            $expression = $this->parseExpression();
        } else {
            $expression = null;
        }

        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\ReturnStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            TokenType::T_KEYWORD_BREAK    => $this->parseBreakStatement(),
            TokenType::T_KEYWORD_CONST    => $this->parseConstStatement(),
            TokenType::T_KEYWORD_CONTINUE => $this->parseContinueStatement(),
            TokenType::T_KEYWORD_FN       => $this->parseFunctionStatement(),
            TokenType::T_KEYWORD_IF       => $this->parseIfStatement(),
            TokenType::T_KEYWORD_PRINT    => $this->parsePrintStatement(),
            TokenType::T_KEYWORD_RETURN   => $this->parseReturnStatement(),
            TokenType::T_KEYWORD_VAR      => $this->parseVarStatement(),
            TokenType::T_KEYWORD_WHILE    => $this->parseWhileStatement(),

            // Fallback
            default => $this->parseExpressionStatement(),
        };
    }

    private function parseStringLiteral(): Literals\StringLiteral
    {
        $token = $this->expect(TokenType::T_STRING);

        return new Literals\StringLiteral(
            line: $token->line,
            value: $token->value,
        );
    }

    private function parseVarStatement(): Statements\VarStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_VAR);
        $name = $this->expect(TokenType::T_IDENTIFIER);
        $this->expect(TokenType::T_ASSIGN);

        $expression = $this->parseExpression();
        $this->expect(TokenType::T_SEMICOLON);

        return new Statements\VarStatement(
            line: $token->line,
            name: $name->value,
            expression: $expression,
        );
    }

    private function parseWhileStatement(): Statements\WhileStatement
    {
        $token = $this->expect(TokenType::T_KEYWORD_WHILE);
        $this->expect(TokenType::T_LEFT_PARENTHESIS);

        $condition = $this->parseExpression();
        $statements = [];

        $this->expect(TokenType::T_RIGHT_PARENTHESIS);
        $this->expect(TokenType::T_LEFT_BRACE);

        while ($this->current->type !== TokenType::T_RIGHT_BRACE) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::T_RIGHT_BRACE);

        return new Statements\WhileStatement(
            line: $token->line,
            condition: $condition,
            statements: $statements,
        );
    }

    /** @phpstan-impure */
    private function expect(TokenType $type): Token
    {
        $token = $this->current;

        if ($token->type === TokenType::T_EOF) {
            throw new UnexpectedEndOfInputException($token->line);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->current = $this->tokenizer->next();

        return $token;
    }
}
