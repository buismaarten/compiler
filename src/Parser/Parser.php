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
    private Token $previous;
    private Token $current;

    public function __construct(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->previous = $this->tokenizer->next();
        $this->current = $this->previous;
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
                $this->next();
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightBracket);

        return new Expressions\ArrayExpression(
            expressions: $expressions,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseAssignmentExpression(Token $token): Expressions\AssignmentExpression
    {
        $this->expect(TokenType::Assign);

        return new Expressions\AssignmentExpression(
            name: $token->value,
            expression: $this->parseExpression(),
            attributes: $this->getAttributes($token),
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
                operator: $operator,
                left: $expression,
                right: $this->parseBinaryExpression($precedence),
                attributes: $this->getAttributes($token),
            );
        }

        return $expression;
    }

    private function parseBooleanLiteral(): Literals\BooleanLiteral
    {
        $token = $this->current;
        $this->next();

        return new Literals\BooleanLiteral(
            value: $token->type === TokenType::KeywordTrue,
            attributes: $this->getAttributes($token),
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
            level: $level,
            attributes: $this->getAttributes($token),
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
            name: $name->value,
            type: $type,
            expression: $expression,
            attributes: $this->getAttributes($token),
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
            level: $level,
            attributes: $this->getAttributes($token),
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
            expression: $expression,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseFloatLiteral(): Literals\FloatLiteral
    {
        $token = $this->expect(TokenType::Float);
        $value = floatval($token->value);

        return new Literals\FloatLiteral(
            value: $value,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseFunctionCallExpression(Token $token): Expressions\FunctionCallExpression
    {
        $arguments = [];

        $this->expect(TokenType::LeftParenthesis);

        while ($this->current->type !== TokenType::RightParenthesis) {
            $arguments[] = $this->parseExpression();

            if ($this->current->type === TokenType::Comma) {
                $this->next();
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        return new Expressions\FunctionCallExpression(
            name: $token->value,
            arguments: $arguments,
            attributes: $this->getAttributes($token),
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
                $this->next();
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
            name: $name->value,
            type: $type,
            parameters: $parameters,
            statements: $statements,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseIdentifierExpression(): AbstractExpression
    {
        $token = $this->expect(TokenType::Identifier);

        $node = match ($this->current->type) {
            TokenType::Assign          => $this->parseAssignmentExpression($token),
            TokenType::Decrement       => $this->parsePostfixUnaryExpression($token),
            TokenType::Increment       => $this->parsePostfixUnaryExpression($token),
            TokenType::LeftParenthesis => $this->parseFunctionCallExpression($token),

            // Fallback
            default => new Expressions\IdentifierExpression(
                name: $token->value,
                attributes: $this->getAttributes($token),
            ),
        };

        // TODO
        // $this->setNodeAttributes($node, $token);

        return $node;
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
            $this->next();
            $this->expect(TokenType::LeftBrace);

            while ($this->current->type !== TokenType::RightBrace) {
                $elseStatements[] = $this->parseStatement();
            }

            $this->expect(TokenType::RightBrace);
        }

        return new Statements\IfStatement(
            condition: $condition,
            thenStatements: $thenStatements,
            elseStatements: $elseStatements,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseIntegerLiteral(): Literals\IntegerLiteral
    {
        $token = $this->expect(TokenType::Integer);
        $value = intval($token->value);

        return new Literals\IntegerLiteral(
            value: $value,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseNamedType(): Types\NamedType
    {
        $token = $this->expect(TokenType::Identifier);

        return new Types\NamedType(
            name: $token->value,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $token = $this->expect(TokenType::KeywordNull);

        return new Literals\NullLiteral(
            value: null,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseParameter(): Parameter
    {
        $token = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        return new Parameter(
            name: $token->value,
            type: $type,
            attributes: $this->getAttributes($token),
        );
    }

    private function parsePostfixUnaryExpression(Token $token): Expressions\UnaryExpression
    {
        $operator = $this->current;
        $this->next();

        return new Expressions\UnaryExpression(
            operator: Expressions\UnaryOperator::fromTokenType($operator->type),
            position: Expressions\UnaryPosition::Postfix,
            expression: new Expressions\IdentifierExpression(
                name: $token->value,
                attributes: $this->getAttributes($token),
            ),
            attributes: $this->getAttributes($token),
        );
    }

    private function parsePrefixUnaryExpression(): Expressions\UnaryExpression
    {
        $token = $this->current;
        $this->next();

        return new Expressions\UnaryExpression(
            operator: Expressions\UnaryOperator::fromTokenType($token->type),
            position: Expressions\UnaryPosition::Prefix,
            expression: $this->parseExpression(),
            attributes: $this->getAttributes($token),
        );
    }

    private function parsePrimaryExpression(): AbstractExpression
    {
        $token = $this->current;

        $node = match ($token->type) {
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

        // TODO
        // $this->setNodeAttributes($node, $token);

        return $node;
    }

    private function parsePrimaryType(): AbstractType
    {
        $token = $this->current;

        $type = match ($token->type) {
            TokenType::LeftParenthesis => $this->parseTupleType(),

            // Fallback
            default => $this->parseNamedType(),
        };

        // TODO
        // $this->setNodeAttributes($type, $token);

        while ($this->current->type === TokenType::QuestionMark || $this->current->type === TokenType::LeftBracket) {
            $token = $this->current;
            $this->next();

            if ($token->type === TokenType::QuestionMark) {
                if ($type instanceof Types\NullableType) {
                    throw new UnexpectedTokenException($token->line, $token->type->name);
                }

                $type = new Types\NullableType(
                    type: $type,
                    attributes: $this->getAttributes($token)
                );
            }

            if ($token->type === TokenType::LeftBracket) {
                $this->expect(TokenType::RightBracket);

                $type = new Types\ArrayType(
                    type: $type,
                    attributes: $this->getAttributes($token)
                );
            }
        }

        // TODO
        // $this->setNodeAttributes($type, $token);

        return $type;
    }

    private function parsePrintStatement(): Statements\PrintStatement
    {
        $token = $this->expect(TokenType::KeywordPrint);
        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\PrintStatement(
            expression: $expression,
            attributes: $this->getAttributes($token),
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
            expression: $expression,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseStatement(): AbstractStatement
    {
        $token = $this->current;

        $node = match ($token->type) {
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

        // TODO
        // $this->setNodeAttributes($node, $token);

        return $node;
    }

    private function parseStringLiteral(): Literals\StringLiteral
    {
        $token = $this->expect(TokenType::String);

        return new Literals\StringLiteral(
            value: $token->value,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseTupleExpression(): AbstractExpression
    {
        $token = $this->expect(TokenType::LeftParenthesis);
        $expressions = [];

        while ($this->current->type !== TokenType::RightParenthesis) {
            $expressions[] = $this->parseExpression();

            if ($this->current->type === TokenType::Comma) {
                $this->next();
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        if (count($expressions) === 1) {
            return $expressions[0];
        }

        return new Expressions\TupleExpression(
            expressions: $expressions,
            attributes: $this->getAttributes($token),
        );
    }

    private function parseTupleType(): Types\TupleType
    {
        $token = $this->expect(TokenType::LeftParenthesis);
        $types = [];

        while ($this->current->type !== TokenType::RightParenthesis) {
            $types[] = $this->parsePrimaryType();

            if ($this->current->type === TokenType::Comma) {
                $this->next();
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        return new Types\TupleType(
            types: $types,
            attributes: $this->getAttributes($token),
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
            name: $name->value,
            type: $type,
            expression: $expression,
            attributes: $this->getAttributes($token),
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
            condition: $condition,
            statements: $statements,
            attributes: $this->getAttributes($token),
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
        $this->previous = $this->current;
        $this->current = $this->tokenizer->next();
    }

    /** @return array<mixed> */
    private function getAttributes(Token $token): array
    {
        $previous = $this->previous;

        return [
            'lineStart' => $token->line,
            'lineEnd'   => $previous->line,
        ];
    }

    // TODO
    // private function setNodeAttributes(AbstractNode $node, Token $token): void
    // {
    //     $node->setAttribute('lineStart', $token->line);
    //     $node->setAttribute('lineEnd', $this->previous->line);
    // }
}
