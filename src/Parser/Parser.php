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

        while ($this->current->type !== TokenType::Eof) {
            $statements[] = $this->parseStatement();
        }

        return $statements;
    }

    private function parseBooleanLiteral(): Literals\BooleanLiteral
    {
        $token = $this->current;
        $this->next();

        return new Literals\BooleanLiteral(
            line: $token->line,
            value: $token->type === TokenType::KeywordTrue,
        );
    }

    private function parseBreakStatement(): Statements\BreakStatement
    {
        $token = $this->expect(TokenType::KeywordBreak);

        if ($this->current->type === TokenType::Integer) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::Semicolon);

        return new Statements\BreakStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseConstStatement(): Statements\ConstStatement
    {
        $token = $this->expect(TokenType::KeywordConst);
        $name = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        $this->expect(TokenType::Assign);

        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\ConstStatement(
            line: $token->line,
            name: $name->value,
            type: $type,
            expression: $expression,
        );
    }

    private function parseContinueStatement(): Statements\ContinueStatement
    {
        $token = $this->expect(TokenType::KeywordContinue);

        if ($this->current->type === TokenType::Integer) {
            $level = $this->parseExpression();
        } else {
            $level = null;
        }

        $this->expect(TokenType::Semicolon);

        return new Statements\ContinueStatement(
            line: $token->line,
            level: $level,
        );
    }

    private function parseExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::Decrement       => $this->parseUnaryExpression(),
            TokenType::ExclamationMark => $this->parseUnaryExpression(),
            TokenType::Float           => $this->parseFloatLiteral(),
            TokenType::Identifier      => $this->parseIdentifierExpression(),
            TokenType::Increment       => $this->parseUnaryExpression(),
            TokenType::Integer         => $this->parseIntegerLiteral(),
            TokenType::KeywordFalse    => $this->parseBooleanLiteral(),
            TokenType::KeywordNull     => $this->parseNullLiteral(),
            TokenType::KeywordTrue     => $this->parseBooleanLiteral(),
            TokenType::Minus           => $this->parseUnaryExpression(),
            TokenType::Plus            => $this->parseUnaryExpression(),
            TokenType::String          => $this->parseStringLiteral(),
            TokenType::Tilde           => $this->parseUnaryExpression(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parseExpressionStatement(): Statements\ExpressionStatement
    {
        $token = $this->current;
        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\ExpressionStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseFloatLiteral(): Literals\FloatLiteral
    {
        $token = $this->expect(TokenType::Float);
        $value = floatval($token->value);

        return new Literals\FloatLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseFunctionStatement(): Statements\FunctionStatement
    {
        $token = $this->expect(TokenType::KeywordFn);
        $name = $this->expect(TokenType::Identifier);
        $parameters = [];
        $statements = [];

        $this->expect(TokenType::LeftParenthesis);

        while ($this->current->type !== TokenType::RightParenthesis) {
            $parameters[] = $this->parseParameter();

            if ($this->current->type === TokenType::Comma) {
                $this->expect(TokenType::Comma);
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        $type = $this->parseType();

        $this->expect(TokenType::LeftBrace);

        while ($this->current->type !== TokenType::RightBrace) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RightBrace);

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
        $token = $this->expect(TokenType::Identifier);

        if ($this->current->type === TokenType::Assign) {
            $this->expect(TokenType::Assign);

            return new Expressions\AssignmentExpression(
                line: $token->line,
                name: $token->value,
                expression: $this->parseExpression(),
            );
        }

        if ($this->current->type === TokenType::LeftParenthesis) {
            $arguments = [];

            $this->expect(TokenType::LeftParenthesis);

            while ($this->current->type !== TokenType::RightParenthesis) {
                $arguments[] = $this->parseExpression();

                if ($this->current->type === TokenType::Comma) {
                    $this->expect(TokenType::Comma);
                } else {
                    break;
                }
            }

            $this->expect(TokenType::RightParenthesis);

            return new Expressions\FunctionCallExpression(
                line: $token->line,
                name: $token->value,
                arguments: $arguments,
            );
        }

        if ($this->current->type === TokenType::Decrement || $this->current->type === TokenType::Increment) {
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
        $token = $this->expect(TokenType::KeywordIf);
        $this->expect(TokenType::LeftParenthesis);

        $condition = $this->parseExpression();
        $thenStatements = [];
        $elseStatements = [];

        $this->expect(TokenType::RightParenthesis);
        $this->expect(TokenType::LeftBrace);

        while ($this->current->type !== TokenType::RightBrace) {
            $thenStatements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RightBrace);

        if ($this->current->type === TokenType::KeywordElse) {
            $this->expect(TokenType::KeywordElse);
            $this->expect(TokenType::LeftBrace);

            while ($this->current->type !== TokenType::RightBrace) {
                $elseStatements[] = $this->parseStatement();
            }

            $this->expect(TokenType::RightBrace);
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
        $token = $this->expect(TokenType::Integer);
        $value = intval($token->value);

        return new Literals\IntegerLiteral(
            line: $token->line,
            value: $value,
        );
    }

    private function parseNamedType(): Types\NamedType
    {
        $token = $this->expect(TokenType::Identifier);

        return new Types\NamedType(
            line: $token->line,
            name: $token->value,
        );
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $token = $this->expect(TokenType::KeywordNull);

        return new Literals\NullLiteral(
            line: $token->line,
        );
    }

    private function parseParameter(): Parameter
    {
        $token = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        return new Parameter(
            line: $token->line,
            name: $token->value,
            type: $type,
        );
    }

    private function parsePrintStatement(): Statements\PrintStatement
    {
        $token = $this->expect(TokenType::KeywordPrint);
        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\PrintStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseReturnStatement(): Statements\ReturnStatement
    {
        $token = $this->expect(TokenType::KeywordReturn);

        if ($this->current->type !== TokenType::Semicolon) {
            $expression = $this->parseExpression();
        } else {
            $expression = null;
        }

        $this->expect(TokenType::Semicolon);

        return new Statements\ReturnStatement(
            line: $token->line,
            expression: $expression,
        );
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            TokenType::KeywordBreak    => $this->parseBreakStatement(),
            TokenType::KeywordConst    => $this->parseConstStatement(),
            TokenType::KeywordContinue => $this->parseContinueStatement(),
            TokenType::KeywordFn       => $this->parseFunctionStatement(),
            TokenType::KeywordIf       => $this->parseIfStatement(),
            TokenType::KeywordPrint    => $this->parsePrintStatement(),
            TokenType::KeywordReturn   => $this->parseReturnStatement(),
            TokenType::KeywordVar      => $this->parseVarStatement(),
            TokenType::KeywordWhile    => $this->parseWhileStatement(),

            // Fallback
            default => $this->parseExpressionStatement(),
        };
    }

    private function parseStringLiteral(): Literals\StringLiteral
    {
        $token = $this->expect(TokenType::String);

        return new Literals\StringLiteral(
            line: $token->line,
            value: $token->value,
        );
    }

    private function parseType(): AbstractType
    {
        $this->expect(TokenType::Colon);

        if ($this->current->type === TokenType::QuestionMark) {
            $token = $this->expect(TokenType::QuestionMark);

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
        $token = $this->expect(TokenType::KeywordVar);
        $name = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        $this->expect(TokenType::Assign);

        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\VarStatement(
            line: $token->line,
            name: $name->value,
            type: $type,
            expression: $expression,
        );
    }

    private function parseWhileStatement(): Statements\WhileStatement
    {
        $token = $this->expect(TokenType::KeywordWhile);
        $this->expect(TokenType::LeftParenthesis);

        $condition = $this->parseExpression();
        $statements = [];

        $this->expect(TokenType::RightParenthesis);
        $this->expect(TokenType::LeftBrace);

        while ($this->current->type !== TokenType::RightBrace) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RightBrace);

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

        if ($token->type === TokenType::Eof) {
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
