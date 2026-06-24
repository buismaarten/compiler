<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\PrettyPrinter;

use Override;
use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\AbstractType;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;

final class PrettyPrinter implements PrettyPrinterInterface
{
    private const INDENT = '    ';
    private const PRECEDENCE_ASSIGNMENT = 1;
    private const PRECEDENCE_UNARY = 100;

    #[Override]
    public function prettyPrint(array $statements): string
    {
        return $this->printStatements($statements, 0);
    }

    #[Override]
    public function prettyPrintExpression(AbstractExpression $expression): string
    {
        return $this->printExpression($expression);
    }

    /** @param AbstractStatement[] $statements */
    private function printStatements(array $statements, int $indentation): string
    {
        $lines = [];

        foreach ($statements as $statement) {
            $lines[] = $this->printStatement($statement, $indentation);
        }

        return implode("\n", $lines);
    }

    private function printStatement(AbstractStatement $statement, int $indentation): string
    {
        $indent = str_repeat(self::INDENT, $indentation);

        if ($statement instanceof Statements\BreakStatement) {
            return $indent.'break'.($statement->level !== null ? ' '.$this->printExpression($statement->level) : '').';';
        }

        if ($statement instanceof Statements\ConstStatement) {
            return sprintf(
                '%sconst %s: %s = %s;',
                $indent,
                $statement->name,
                $this->printType($statement->type),
                $this->printExpression($statement->expression),
            );
        }

        if ($statement instanceof Statements\ContinueStatement) {
            return $indent.'continue'.($statement->level !== null ? ' '.$this->printExpression($statement->level) : '').';';
        }

        if ($statement instanceof Statements\ExpressionStatement) {
            return $indent.$this->printExpression($statement->expression).';';
        }

        if ($statement instanceof Statements\FunctionStatement) {
            $parameters = array_map(
                fn (Parameter $parameter): string => sprintf('%s: %s', $parameter->name, $this->printType($parameter->type)),
                $statement->parameters,
            );

            $header = sprintf(
                '%sfn %s(%s): %s',
                $indent,
                $statement->name,
                implode(', ', $parameters),
                $this->printType($statement->type),
            );

            if ($statement->statements === []) {
                return $header.' {}';
            }

            return $header." {\n"
                .$this->printStatements($statement->statements, $indentation + 1)
                ."\n"
                .$indent
                .'}';
        }

        if ($statement instanceof Statements\IfStatement) {
            $output = sprintf('%sif (%s)', $indent, $this->printExpression($statement->condition));

            if ($statement->thenStatements === []) {
                $output .= ' {}';
            } else {
                $output .= " {\n"
                   .$this->printStatements($statement->thenStatements, $indentation + 1)
                   ."\n"
                   .$indent
                   .'}';
            }

            if ($statement->elseStatements === []) {
                return $output;
            }

            return $output." else {\n"
               .$this->printStatements($statement->elseStatements, $indentation + 1)
               ."\n"
               .$indent
               .'}';
        }

        if ($statement instanceof Statements\PrintStatement) {
            return sprintf('%sprint %s;', $indent, $this->printExpression($statement->expression));
        }

        if ($statement instanceof Statements\ReturnStatement) {
            return $indent.'return'.($statement->expression !== null ? ' '.$this->printExpression($statement->expression) : '').';';
        }

        if ($statement instanceof Statements\VarStatement) {
            return sprintf(
                '%svar %s: %s = %s;',
                $indent,
                $statement->name,
                $this->printType($statement->type),
                $this->printExpression($statement->expression),
            );
        }

        if ($statement instanceof Statements\WhileStatement) {
            $header = sprintf('%swhile (%s)', $indent, $this->printExpression($statement->condition));

            if ($statement->statements === []) {
                return $header.' {}';
            }

            return $header." {\n"
               .$this->printStatements($statement->statements, $indentation + 1)
               ."\n"
               .$indent
               .'}';
        }

        throw new \RuntimeException('Unsupported statement node: '.$statement::class);
    }

    private function printExpression(AbstractExpression $expression, int $minimumPrecedence = 0): string
    {
        if ($expression instanceof Expressions\ArrayExpression) {
            $values = array_map(fn (AbstractExpression $value): string => $this->printExpression($value), $expression->expressions);

            return '['.implode(', ', $values).']';
        }

        if ($expression instanceof Expressions\AssignmentExpression) {
            $precedence = self::PRECEDENCE_ASSIGNMENT;
            $printed = sprintf(
                '%s = %s',
                $expression->name,
                $this->printExpression($expression->expression, $precedence),
            );

            return $precedence < $minimumPrecedence ? '('.$printed.')' : $printed;
        }

        if ($expression instanceof Expressions\BinaryExpression) {
            $precedence = $expression->operator->precedence();
            $printed = sprintf(
                '%s %s %s',
                $this->printExpression($expression->left, $precedence),
                $this->printBinaryOperator($expression->operator),
                $this->printExpression($expression->right, $precedence + 1),
            );

            return $precedence < $minimumPrecedence ? '('.$printed.')' : $printed;
        }

        if ($expression instanceof Expressions\FunctionCallExpression) {
            $arguments = array_map(fn (AbstractExpression $argument): string => $this->printExpression($argument), $expression->arguments);

            return sprintf('%s(%s)', $expression->name, implode(', ', $arguments));
        }

        if ($expression instanceof Expressions\IdentifierExpression) {
            return $expression->name;
        }

        if ($expression instanceof Expressions\TupleExpression) {
            $values = array_map(fn (AbstractExpression $value): string => $this->printExpression($value), $expression->expressions);

            return '('.implode(', ', $values).')';
        }

        if ($expression instanceof Expressions\UnaryExpression) {
            $operator = $this->printUnaryOperator($expression->operator);

            if ($expression->position === Expressions\UnaryPosition::Prefix) {
                $printed = $operator.$this->printExpression($expression->expression, self::PRECEDENCE_UNARY);
            } else {
                $printed = $this->printExpression($expression->expression, self::PRECEDENCE_UNARY).$operator;
            }

            return $minimumPrecedence > self::PRECEDENCE_UNARY ? '('.$printed.')' : $printed;
        }

        if ($expression instanceof Literals\BooleanLiteral) {
            return $expression->value ? 'true' : 'false';
        }

        if ($expression instanceof Literals\FloatLiteral) {
            $encoded = json_encode($expression->value, JSON_PRESERVE_ZERO_FRACTION);

            if ($encoded === false) {
                throw new \RuntimeException('Unable to encode float literal.');
            }

            return $encoded;
        }

        if ($expression instanceof Literals\IntegerLiteral) {
            return (string) $expression->value;
        }

        if ($expression instanceof Literals\NullLiteral) {
            return 'null';
        }

        if ($expression instanceof Literals\StringLiteral) {
            return '"'.addcslashes($expression->value, "\\\"\n\r\t\v\f").'"';
        }

        throw new \RuntimeException('Unsupported expression node: '.$expression::class);
    }

    private function printType(AbstractType $type): string
    {
        if ($type instanceof Types\ArrayType) {
            return $this->printType($type->type).'[]';
        }

        if ($type instanceof Types\NamedType) {
            return $type->name;
        }

        if ($type instanceof Types\NullableType) {
            return $this->printType($type->type).'?';
        }

        if ($type instanceof Types\TupleType) {
            $types = array_map(fn (AbstractType $child): string => $this->printType($child), $type->types);

            return '('.implode(', ', $types).')';
        }

        throw new \RuntimeException('Unsupported type node: '.$type::class);
    }

    private function printBinaryOperator(Expressions\BinaryOperator $operator): string
    {
        return match ($operator) {
            Expressions\BinaryOperator::BitwiseAnd       => '&',
            Expressions\BinaryOperator::BitwiseOr        => '|',
            Expressions\BinaryOperator::BitwiseXor       => '^',
            Expressions\BinaryOperator::BooleanAnd       => '&&',
            Expressions\BinaryOperator::BooleanOr        => '||',
            Expressions\BinaryOperator::Divide           => '/',
            Expressions\BinaryOperator::Equal            => '==',
            Expressions\BinaryOperator::GreaterThan      => '>',
            Expressions\BinaryOperator::GreaterThanEqual => '>=',
            Expressions\BinaryOperator::Identical        => '===',
            Expressions\BinaryOperator::LessThan         => '<',
            Expressions\BinaryOperator::LessThanEqual    => '<=',
            Expressions\BinaryOperator::Minus            => '-',
            Expressions\BinaryOperator::Modulo           => '%',
            Expressions\BinaryOperator::Multiply         => '*',
            Expressions\BinaryOperator::NotEqual         => '!=',
            Expressions\BinaryOperator::NotIdentical     => '!==',
            Expressions\BinaryOperator::Plus             => '+',
            Expressions\BinaryOperator::ShiftLeft        => '<<',
            Expressions\BinaryOperator::ShiftRight       => '>>',
            Expressions\BinaryOperator::Spaceship        => '<=>',
        };
    }

    private function printUnaryOperator(Expressions\UnaryOperator $operator): string
    {
        return match ($operator) {
            Expressions\UnaryOperator::BitwiseNot => '~',
            Expressions\UnaryOperator::BooleanNot => '!',
            Expressions\UnaryOperator::Decrement  => '--',
            Expressions\UnaryOperator::Increment  => '++',
            Expressions\UnaryOperator::Minus      => '-',
            Expressions\UnaryOperator::Plus       => '+',
        };
    }
}
