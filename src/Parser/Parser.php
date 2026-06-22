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

    private function parseArrayExpression(): Expressions\ArrayExpression
    {
        $token = $this->expect(TokenType::LeftBracket);
        $expressions = [];

        while ($this->current->type !== TokenType::RightBracket) {
            $expressions[] = $this->parseExpression();

            if ($this->current->type === TokenType::Comma) {
                $this->expect(TokenType::Comma);
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightBracket);

        return new Expressions\ArrayExpression(
            line: $token->line,
            expressions: $expressions,
        );
    }

    private function parseAssignmentExpression(Token $token): Expressions\AssignmentExpression
    {
        $this->expect(TokenType::Assign);

        return new Expressions\AssignmentExpression(
            line: $token->line,
            name: $token->value,
            expression: $this->parseExpression(),
        );
    }

    private function parseBinaryExpression(int $minimumPrecedence = 0): AbstractExpression
    {
        $expression = $this->parsePrimaryExpression();

        while (
            ($operator = Expressions\BinaryOperator::fromTokenType($this->current->type)) &&
            ($precedence = $operator->precedence()) >= $minimumPrecedence
        ) {
            $token = $this->current;
            $this->next();

            $expression = new Expressions\BinaryExpression(
                line: $token->line,
                left: $expression,
                operator: $operator,
                right: $this->parseBinaryExpression($precedence),
            );
        }

        return $expression;
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
        return $this->parseBinaryExpression();
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

    private function parseFunctionCallExpression(Token $token): Expressions\FunctionCallExpression
    {
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

        return match ($this->current->type) {
            TokenType::Assign          => $this->parseAssignmentExpression($token),
            TokenType::Decrement       => $this->parsePostfixUnaryExpression($token),
            TokenType::Increment       => $this->parsePostfixUnaryExpression($token),
            TokenType::LeftParenthesis => $this->parseFunctionCallExpression($token),

            // Fallback
            default => new Expressions\IdentifierExpression(
                line: $token->line,
                name: $token->value,
            ),
        };
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

    private function parsePostfixUnaryExpression(Token $token): Expressions\UnaryExpression
    {
        $operator = $this->current;
        $this->next();

        return new Expressions\UnaryExpression(
            line: $token->line,
            position: Expressions\UnaryPosition::Postfix,
            operator: Expressions\UnaryOperator::fromTokenType($operator->type),
            expression: new Expressions\IdentifierExpression(
                line: $token->line,
                name: $token->value,
            ),
        );
    }

    private function parsePrefixUnaryExpression(): Expressions\UnaryExpression
    {
        $token = $this->current;
        $this->next();

        return new Expressions\UnaryExpression(
            line: $token->line,
            position: Expressions\UnaryPosition::Prefix,
            operator: Expressions\UnaryOperator::fromTokenType($token->type),
            expression: $this->parseExpression(),
        );
    }

    private function parsePrimaryExpression(): AbstractExpression
    {
        return match ($this->current->type) {
            TokenType::BitwiseNot      => $this->parsePrefixUnaryExpression(),
            TokenType::BooleanNot      => $this->parsePrefixUnaryExpression(),
            TokenType::Decrement       => $this->parsePrefixUnaryExpression(),
            TokenType::Float           => $this->parseFloatLiteral(),
            TokenType::Identifier      => $this->parseIdentifierExpression(),
            TokenType::Increment       => $this->parsePrefixUnaryExpression(),
            TokenType::Integer         => $this->parseIntegerLiteral(),
            TokenType::KeywordFalse    => $this->parseBooleanLiteral(),
            TokenType::KeywordNull     => $this->parseNullLiteral(),
            TokenType::KeywordTrue     => $this->parseBooleanLiteral(),
            TokenType::LeftBracket     => $this->parseArrayExpression(),
            TokenType::LeftParenthesis => $this->parseTupleExpression(),
            TokenType::Minus           => $this->parsePrefixUnaryExpression(),
            TokenType::Plus            => $this->parsePrefixUnaryExpression(),
            TokenType::String          => $this->parseStringLiteral(),

            // Fallback
            default => throw new UnexpectedTokenException($this->current->line, $this->current->type->name),
        };
    }

    private function parsePrimaryType(): AbstractType
    {
        $type = match ($this->current->type) {
            TokenType::LeftParenthesis => $this->parseTupleType(),

            // Fallback
            default => $this->parseNamedType(),
        };

        while ($this->current->type === TokenType::QuestionMark || $this->current->type === TokenType::LeftBracket) {
            $token = $this->current;
            $this->next();

            if ($token->type === TokenType::QuestionMark) {
                if ($type instanceof Types\NullableType) {
                    throw new UnexpectedTokenException($token->line, $token->type->name);
                }

                $type = new Types\NullableType(
                    line: $token->line,
                    type: $type,
                );
            }

            if ($token->type === TokenType::LeftBracket) {
                $this->expect(TokenType::RightBracket);

                $type = new Types\ArrayType(
                    line: $token->line,
                    type: $type,
                );
            }
        }

        return $type;
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

    private function parseTupleExpression(): AbstractExpression
    {
        $token = $this->expect(TokenType::LeftParenthesis);
        $expressions = [];

        while ($this->current->type !== TokenType::RightParenthesis) {
            $expressions[] = $this->parseExpression();

            if ($this->current->type === TokenType::Comma) {
                $this->expect(TokenType::Comma);
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        if (count($expressions) === 1) {
            return $expressions[0];
        }

        return new Expressions\TupleExpression(
            line: $token->line,
            expressions: $expressions,
        );
    }

    private function parseTupleType(): Types\TupleType
    {
        $token = $this->expect(TokenType::LeftParenthesis);
        $types = [];

        while ($this->current->type !== TokenType::RightParenthesis) {
            $types[] = $this->parsePrimaryType();

            if ($this->current->type === TokenType::Comma) {
                $this->expect(TokenType::Comma);
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        return new Types\TupleType(
            line: $token->line,
            types: $types,
        );
    }

    private function parseType(): AbstractType
    {
        $this->expect(TokenType::Colon);

        return $this->parsePrimaryType();
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
