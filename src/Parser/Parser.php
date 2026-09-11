<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Parser;

use Package\Core\Location;
use Package\Exceptions\Parser\ExpectedTokenException;
use Package\Exceptions\Parser\UnexpectedEndOfInputException;
use Package\Exceptions\Parser\UnexpectedTokenException;
use Package\Language\BinaryOperator;
use Package\Language\Primitive;
use Package\Language\UnaryOperator;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Expressions\AbstractExpression;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Statements\AbstractStatement;
use Package\Nodes\Types;
use Package\Nodes\Types\AbstractType;
use Package\Tokenizer\Token;
use Package\Tokenizer\Tokenizer;
use Package\Tokenizer\TokenType;

final class Parser
{
    private Tokenizer $tokenizer;
    private Token $previous;
    private Token $current;

    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->previous = $this->tokenizer->next();
        $this->current = $this->previous;
    }

    public function parse(): Block
    {
        $token = $this->current;
        $statements = [];

        while ($this->current->type !== TokenType::Eof) {
            $statements[] = $this->parseStatement();
        }

        return new Block(
            location: $this->createLocation($token),
            statements: $statements,
        );
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
            location: $this->createLocation($token),
            expressions: $expressions,
        );
    }

    private function parseAssignmentExpression(Token $identifier): Expressions\AssignmentExpression
    {
        $this->expect(TokenType::Assign);

        $expression = $this->parseExpression();

        return new Expressions\AssignmentExpression(
            location: $this->createLocation($identifier),
            name: $identifier->value,
            expression: $expression,
        );
    }

    private function parseBinaryExpression(int $minimumPrecedence = 0): AbstractExpression
    {
        $expression = $this->parsePrimaryExpression();

        while (
            ($operator = self::resolveBinaryOperator($this->current->type)) &&
            ($precedence = $operator->precedence()) >= $minimumPrecedence
        ) {
            $token = $this->current;
            $this->next();

            $right = $this->parseBinaryExpression($precedence);

            $expression = new Expressions\BinaryExpression(
                location: $this->createLocation($token),
                operator: $operator,
                left: $expression,
                right: $right,
            );
        }

        return $expression;
    }

    private function parseBlock(): Block
    {
        $token = $this->expect(TokenType::LeftBrace);
        $statements = [];

        while ($this->current->type !== TokenType::RightBrace) {
            $statements[] = $this->parseStatement();
        }

        $this->expect(TokenType::RightBrace);

        return new Block(
            location: $this->createLocation($token),
            statements: $statements,
        );
    }

    private function parseBlockStatement(): Statements\BlockStatement
    {
        $block = $this->parseBlock();

        return new Statements\BlockStatement(
            location: $block->location,
            block: $block,
        );
    }

    private function parseBooleanLiteral(): Literals\BooleanLiteral
    {
        $token = $this->current;
        $this->next();

        return new Literals\BooleanLiteral(
            location: $this->createLocation($token),
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
            location: $this->createLocation($token),
            level: $level,
        );
    }

    private function parseConstantStatement(): Statements\ConstantStatement
    {
        $token = $this->expect(TokenType::KeywordConst);
        $identifier = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        $this->expect(TokenType::Assign);

        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\ConstantStatement(
            location: $this->createLocation($token),
            name: $identifier->value,
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
            location: $this->createLocation($token),
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
            location: $this->createLocation($token),
            expression: $expression,
        );
    }

    private function parseFloatLiteral(): Literals\FloatLiteral
    {
        $token = $this->expect(TokenType::Float);
        $value = floatval($token->value);

        return new Literals\FloatLiteral(
            location: $this->createLocation($token),
            value: $value,
        );
    }

    private function parseFunctionCallExpression(Token $identifier): Expressions\FunctionCallExpression
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
            location: $this->createLocation($identifier),
            name: $identifier->value,
            arguments: $arguments,
        );
    }

    private function parseFunctionStatement(): Statements\FunctionStatement
    {
        $token = $this->expect(TokenType::KeywordFn);
        $identifier = $this->expect(TokenType::Identifier);
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
        $statements = $this->parseBlock();

        return new Statements\FunctionStatement(
            location: $this->createLocation($token),
            name: $identifier->value,
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

            default => new Expressions\IdentifierExpression(
                location: $this->createLocation($token),
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
        $elseStatements = null;

        $this->expect(TokenType::RightParenthesis);

        $thenStatements = $this->parseBlock();

        if ($this->current->type === TokenType::KeywordElse) {
            $this->next();
            $elseStatements = $this->parseBlock();
        }

        return new Statements\IfStatement(
            location: $this->createLocation($token),
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
            location: $this->createLocation($token),
            value: $value,
        );
    }

    private function parseNamedType(): AbstractType
    {
        $token = $this->expect(TokenType::Identifier);
        $type = Primitive::tryFrom($token->value);

        if ($type !== null) {
            return new Types\PrimitiveType(
                location: $this->createLocation($token),
                type: $type,
            );
        }

        return new Types\NamedType(
            location: $this->createLocation($token),
            name: $token->value,
        );
    }

    private function parseNullLiteral(): Literals\NullLiteral
    {
        $token = $this->expect(TokenType::KeywordNull);

        return new Literals\NullLiteral(
            location: $this->createLocation($token),
        );
    }

    private function parseParameter(): Parameter
    {
        $token = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        return new Parameter(
            location: $this->createLocation($token),
            name: $token->value,
            type: $type,
        );
    }

    private function parsePostfixUnaryExpression(Token $identifier): Expressions\UnaryExpression
    {
        $token = $this->current;
        $this->next();

        if (($operator = self::resolveUnaryOperator($token->type)) === null) {
            throw new UnexpectedTokenException($token->location->startLine, $token->type->name);
        }

        return new Expressions\UnaryExpression(
            location: $this->createLocation($identifier, $token),
            prefix: false,
            operator: $operator,
            expression: new Expressions\IdentifierExpression(
                location: $this->createLocation($identifier, $identifier),
                name: $identifier->value,
            ),
        );
    }

    private function parsePrefixUnaryExpression(): Expressions\UnaryExpression
    {
        $token = $this->current;
        $this->next();

        if (($operator = self::resolveUnaryOperator($token->type)) === null) {
            throw new UnexpectedTokenException($token->location->startLine, $token->type->name);
        }

        $expression = $this->parseExpression();

        return new Expressions\UnaryExpression(
            location: $this->createLocation($token),
            prefix: true,
            operator: $operator,
            expression: $expression,
        );
    }

    private function parsePrimaryExpression(): AbstractExpression
    {
        $token = $this->current;

        return match ($token->type) {
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

            default => throw new UnexpectedTokenException($token->location->startLine, $token->type->name),
        };
    }

    private function parsePrimaryType(): AbstractType
    {
        $type = match ($this->current->type) {
            TokenType::LeftParenthesis => $this->parseTupleType(),

            default => $this->parseNamedType(),
        };

        while ($this->current->type === TokenType::QuestionMark || $this->current->type === TokenType::LeftBracket) {
            $token = $this->current;
            $this->next();

            if ($token->type === TokenType::QuestionMark) {
                $type = new Types\NullableType(
                    location: $this->createLocation($token),
                    type: $type,
                );
            }

            if ($token->type === TokenType::LeftBracket) {
                $this->expect(TokenType::RightBracket);

                $type = new Types\ArrayType(
                    location: $this->createLocation($token),
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
            location: $this->createLocation($token),
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
            location: $this->createLocation($token),
            expression: $expression,
        );
    }

    private function parseStatement(): AbstractStatement
    {
        return match ($this->current->type) {
            TokenType::KeywordBreak    => $this->parseBreakStatement(),
            TokenType::KeywordConst    => $this->parseConstantStatement(),
            TokenType::KeywordContinue => $this->parseContinueStatement(),
            TokenType::KeywordFn       => $this->parseFunctionStatement(),
            TokenType::KeywordIf       => $this->parseIfStatement(),
            TokenType::KeywordPrint    => $this->parsePrintStatement(),
            TokenType::KeywordReturn   => $this->parseReturnStatement(),
            TokenType::KeywordVar      => $this->parseVariableStatement(),
            TokenType::KeywordWhile    => $this->parseWhileStatement(),
            TokenType::LeftBrace       => $this->parseBlockStatement(),

            default => $this->parseExpressionStatement(),
        };
    }

    private function parseStringLiteral(): Literals\StringLiteral
    {
        $token = $this->expect(TokenType::String);

        return new Literals\StringLiteral(
            location: $this->createLocation($token),
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
            location: $this->createLocation($token),
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
                $this->next();
            } else {
                break;
            }
        }

        $this->expect(TokenType::RightParenthesis);

        return new Types\TupleType(
            location: $this->createLocation($token),
            types: $types,
        );
    }

    private function parseType(): AbstractType
    {
        $this->expect(TokenType::Colon);

        return $this->parsePrimaryType();
    }

    private function parseVariableStatement(): Statements\VariableStatement
    {
        $token = $this->expect(TokenType::KeywordVar);
        $identifier = $this->expect(TokenType::Identifier);
        $type = $this->parseType();

        $this->expect(TokenType::Assign);

        $expression = $this->parseExpression();

        $this->expect(TokenType::Semicolon);

        return new Statements\VariableStatement(
            location: $this->createLocation($token),
            name: $identifier->value,
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

        $statements = $this->parseBlock();

        return new Statements\WhileStatement(
            location: $this->createLocation($token),
            condition: $condition,
            statements: $statements,
        );
    }

    private function createLocation(Token $startToken, ?Token $endToken = null): Location
    {
        if ($endToken === null) {
            $endToken = $this->previous;
        }

        return new Location(
            startLine: $startToken->location->startLine,
            startOffset: $startToken->location->startOffset,
            endLine: $endToken->location->endLine,
            endOffset: $endToken->location->endOffset,
        );
    }

    /** @phpstan-impure */
    private function expect(TokenType $type): Token
    {
        $token = $this->current;

        if ($token->type === TokenType::Eof) {
            throw new UnexpectedEndOfInputException($token->location->startLine);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->location->startLine, $type->name, $token->type->name);
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

    private static function resolveBinaryOperator(TokenType $tokenType): ?BinaryOperator
    {
        return match ($tokenType) {
            TokenType::BitwiseAnd       => BinaryOperator::BitwiseAnd,
            TokenType::BitwiseOr        => BinaryOperator::BitwiseOr,
            TokenType::BitwiseXor       => BinaryOperator::BitwiseXor,
            TokenType::BooleanAnd       => BinaryOperator::BooleanAnd,
            TokenType::BooleanOr        => BinaryOperator::BooleanOr,
            TokenType::Divide           => BinaryOperator::Divide,
            TokenType::Equal            => BinaryOperator::Equal,
            TokenType::GreaterThan      => BinaryOperator::GreaterThan,
            TokenType::GreaterThanEqual => BinaryOperator::GreaterThanEqual,
            TokenType::Identical        => BinaryOperator::Identical,
            TokenType::LessThan         => BinaryOperator::LessThan,
            TokenType::LessThanEqual    => BinaryOperator::LessThanEqual,
            TokenType::Minus            => BinaryOperator::Minus,
            TokenType::Modulo           => BinaryOperator::Modulo,
            TokenType::Multiply         => BinaryOperator::Multiply,
            TokenType::NotEqual         => BinaryOperator::NotEqual,
            TokenType::NotIdentical     => BinaryOperator::NotIdentical,
            TokenType::Plus             => BinaryOperator::Plus,
            TokenType::ShiftLeft        => BinaryOperator::ShiftLeft,
            TokenType::ShiftRight       => BinaryOperator::ShiftRight,
            TokenType::Spaceship        => BinaryOperator::Spaceship,

            default => null,
        };
    }

    private static function resolveUnaryOperator(TokenType $tokenType): ?UnaryOperator
    {
        return match ($tokenType) {
            TokenType::BitwiseNot => UnaryOperator::BitwiseNot,
            TokenType::BooleanNot => UnaryOperator::BooleanNot,
            TokenType::Decrement  => UnaryOperator::Decrement,
            TokenType::Increment  => UnaryOperator::Increment,
            TokenType::Minus      => UnaryOperator::Minus,
            TokenType::Plus       => UnaryOperator::Plus,

            default => null,
        };
    }
}
