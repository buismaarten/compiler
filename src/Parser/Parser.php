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
use Package\Nodes\AbstractType;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
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

        while ($this->current->type !== TokenType::EOF) {
            $statements[] = $this->parseStatement();
        }

        return $statements;
    }

    private function parseBooleanLiteral(bool $value): Literals\BooleanLiteral
    {
        if ($value) {
            $token = $this->expect(TokenType::KEYWORD_TRUE);
        } else {
            $token = $this->expect(TokenType::KEYWORD_FALSE);
        }

        return new Literals\BooleanLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseBreakStatement(): Statements\BreakStatement
    {
        $token = $this->expect(TokenType::KEYWORD_BREAK);

        if ($this->current->type === TokenType::INTEGER) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::SEMICOLON);

        return new Statements\BreakStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseConstStatement(): Statements\ConstStatement
    {
        $token = $this->expect(TokenType::KEYWORD_CONST);
        $name = $this->expect(TokenType::IDENTIFIER);
        $type = $this->parseType();

        $this->expect(TokenType::ASSIGN);

        $expression = $this->parseExpression();

        $this->expect(TokenType::SEMICOLON);

        return new Statements\ConstStatement(
            line: $token->line,
            name: $name->value,
            type: $type,
            expression: $expression,
        );
    }

    private function parseContinueStatement(): Statements\ContinueStatement
    {
        $token = $this->expect(TokenType::KEYWORD_CONTINUE);

        if ($this->current->type === TokenType::INTEGER) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::SEMICOLON);

        return new Statements\ContinueStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::DECREMENT        => $this->parseUnaryExpression(),
            TokenType::EXCLAMATION_MARK => $this->parseUnaryExpression(),
            TokenType::FLOAT            => $this->parseFloatLiteral(),
            TokenType::IDENTIFIER       => $this->parseIdentifierExpression(),
            TokenType::INCREMENT        => $this->parseUnaryExpression(),
            TokenType::INTEGER          => $this->parseIntegerLiteral(),
            TokenType::KEYWORD_FALSE    => $this->parseBooleanLiteral(false),
            TokenType::KEYWORD_NULL     => $this->parseNullLiteral(),
            TokenType::KEYWORD_TRUE     => $this->parseBooleanLiteral(true),
            TokenType::MINUS            => $this->parseUnaryExpression(),
            TokenType::PLUS             => $this->parseUnaryExpression(),
            TokenType::STRING           => $this->parseStringLiteral(),
            TokenType::TILDE            => $this->parseUnaryExpression(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parseExpressionStatement(): Statements\ExpressionStatement
    {
        $token = $this->current;
        $expression = $this->parseExpression();

        $this->expect(TokenType::SEMICOLON);

        return new Statements\ExpressionStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseFloatLiteral(): Literals\FloatLiteral
    {
        $token = $this->expect(TokenType::FLOAT);
        $value = floatval($token->value);

        return new Literals\FloatLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseFunctionStatement(): Statements\FunctionStatement
    {
        $token = $this->expect(TokenType::KEYWORD_FN);
        $name = $this->expect(TokenType::IDENTIFIER);
        $parameters = [];
        $statements = [];

        $this->expect(TokenType::LEFT_PARENTHESIS);

        while ($this->current->type !== TokenType::RIGHT_PARENTHESIS) {
            $parameters[] = $this->parseParameter();

            if ($this->current->type === TokenType::COMMA) {
                $this->expect(TokenType::COMMA);
            } else {
                break;
            }
        }

        $this->expect(TokenType::RIGHT_PARENTHESIS);

        $type = $this->parseType();

        $this->expect(TokenType::LEFT_BRACE);

        while ($this->current->type !== TokenType::RIGHT_BRACE) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RIGHT_BRACE);

        return new Statements\FunctionStatement(
            line: $token->line,
            name: $name->value,
            type: $type,
            parameters: $parameters,
            statements: $statements,
        );
    }

    private function parseIdentifierExpression(): AbstractExpression
    {
        $token = $this->expect(TokenType::IDENTIFIER);

        if ($this->current->type === TokenType::ASSIGN) {
            $this->expect(TokenType::ASSIGN);

            return new Expressions\AssignmentExpression(
                line: $token->line,
                name: $token->value,
                expression: $this->parseExpression(),
            );
        }

        if ($this->current->type === TokenType::LEFT_PARENTHESIS) {
            $arguments = [];

            $this->expect(TokenType::LEFT_PARENTHESIS);

            while ($this->current->type !== TokenType::RIGHT_PARENTHESIS) {
                $arguments[] = $this->parseExpression();

                if ($this->current->type === TokenType::COMMA) {
                    $this->expect(TokenType::COMMA);
                } else {
                    break;
                }
            }

            $this->expect(TokenType::RIGHT_PARENTHESIS);

            return new Expressions\FunctionCallExpression(
                line: $token->line,
                name: $token->value,
                arguments: $arguments,
            );
        }

        if ($this->current->type === TokenType::DECREMENT || $this->current->type === TokenType::INCREMENT) {
            $operator = $this->current;
            $this->next();

            return new Expressions\UnaryExpression(
                line: $token->line,
                prefix: false,
                operator: Expressions\UnaryOperator::fromTokenType($operator->type),
                expression: new Expressions\IdentifierExpression(
                    line: $token->line,
                    name: $token->value,
                ),
            );
        }

        return new Expressions\IdentifierExpression(
            line: $token->line,
            name: $token->value,
        );
    }

    private function parseIfStatement(): Statements\IfStatement
    {
        $token = $this->expect(TokenType::KEYWORD_IF);
        $this->expect(TokenType::LEFT_PARENTHESIS);

        $condition = $this->parseExpression();
        $thenStatements = [];
        $elseStatements = [];

        $this->expect(TokenType::RIGHT_PARENTHESIS);
        $this->expect(TokenType::LEFT_BRACE);

        while ($this->current->type !== TokenType::RIGHT_BRACE) {
            $thenStatements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RIGHT_BRACE);

        if ($this->current->type === TokenType::KEYWORD_ELSE) {
            $this->expect(TokenType::KEYWORD_ELSE);
            $this->expect(TokenType::LEFT_BRACE);

            while ($this->current->type !== TokenType::RIGHT_BRACE) {
                $elseStatements[] = $this->parseStatement();
            }

            $this->expect(TokenType::RIGHT_BRACE);
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
        $token = $this->expect(TokenType::INTEGER);
        $value = intval($token->value);

        return new Literals\IntegerLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseNamedType(): Types\NamedType
    {
        $token = $this->expect(TokenType::IDENTIFIER);

        return new Types\NamedType(
            line: $token->line,
            name: $token->value,
        );
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $token = $this->expect(TokenType::KEYWORD_NULL);

        return new Literals\NullLiteral(
            line: $token->line,
        );
    }

    private function parseParameter(): Parameter
    {
        $token = $this->expect(TokenType::IDENTIFIER);
        $type = $this->parseType();

        return new Parameter(
            line: $token->line,
            name: $token->value,
            type: $type,
        );
    }

    private function parsePrintStatement(): Statements\PrintStatement
    {
        $token = $this->expect(TokenType::KEYWORD_PRINT);
        $expression = $this->parseExpression();

        $this->expect(TokenType::SEMICOLON);

        return new Statements\PrintStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseReturnStatement(): Statements\ReturnStatement
    {
        $token = $this->expect(TokenType::KEYWORD_RETURN);

        if ($this->current->type !== TokenType::SEMICOLON) {
            $expression = $this->parseExpression();
        } else {
            $expression = null;
        }

        $this->expect(TokenType::SEMICOLON);

        return new Statements\ReturnStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            TokenType::KEYWORD_BREAK    => $this->parseBreakStatement(),
            TokenType::KEYWORD_CONST    => $this->parseConstStatement(),
            TokenType::KEYWORD_CONTINUE => $this->parseContinueStatement(),
            TokenType::KEYWORD_FN       => $this->parseFunctionStatement(),
            TokenType::KEYWORD_IF       => $this->parseIfStatement(),
            TokenType::KEYWORD_PRINT    => $this->parsePrintStatement(),
            TokenType::KEYWORD_RETURN   => $this->parseReturnStatement(),
            TokenType::KEYWORD_VAR      => $this->parseVarStatement(),
            TokenType::KEYWORD_WHILE    => $this->parseWhileStatement(),

            // Fallback
            default => $this->parseExpressionStatement(),
        };
    }

    private function parseStringLiteral(): Literals\StringLiteral
    {
        $token = $this->expect(TokenType::STRING);

        return new Literals\StringLiteral(
            line: $token->line,
            value: $token->value,
        );
    }

    private function parseType(): AbstractType
    {
        $this->expect(TokenType::COLON);

        if ($this->current->type === TokenType::QUESTION_MARK) {
            $token = $this->expect(TokenType::QUESTION_MARK);

            return new Types\NullableType(
                line: $token->line,
                type: $this->parseNamedType(),
            );
        }

        return $this->parseNamedType();
    }

    private function parseUnaryExpression(): Expressions\UnaryExpression
    {
        $token = $this->current;
        $this->next();

        return new Expressions\UnaryExpression(
            line: $token->line,
            prefix: true,
            operator: Expressions\UnaryOperator::fromTokenType($token->type),
            expression: $this->parseExpression(),
        );
    }

    private function parseVarStatement(): Statements\VarStatement
    {
        $token = $this->expect(TokenType::KEYWORD_VAR);
        $name = $this->expect(TokenType::IDENTIFIER);
        $type = $this->parseType();

        $this->expect(TokenType::ASSIGN);

        $expression = $this->parseExpression();

        $this->expect(TokenType::SEMICOLON);

        return new Statements\VarStatement(
            line: $token->line,
            name: $name->value,
            type: $type,
            expression: $expression,
        );
    }

    private function parseWhileStatement(): Statements\WhileStatement
    {
        $token = $this->expect(TokenType::KEYWORD_WHILE);
        $this->expect(TokenType::LEFT_PARENTHESIS);

        $condition = $this->parseExpression();
        $statements = [];

        $this->expect(TokenType::RIGHT_PARENTHESIS);
        $this->expect(TokenType::LEFT_BRACE);

        while ($this->current->type !== TokenType::RIGHT_BRACE) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RIGHT_BRACE);

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

        if ($token->type === TokenType::EOF) {
            throw new UnexpectedEndOfInputException($token->line);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->next();

        return $token;
    }

    /** @phpstan-impure */
    private function next(): void
    {
        $this->current = $this->tokenizer->next();
    }
}
