<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\BinaryOperator;
use Package\Common\Language\Primitive;
use Package\Semantic\Types\ErrorType;
use Package\Types;
use Package\Types\AbstractType;

final class BinaryExpressionResolver
{
    public static function resolve(BinaryOperator $operator, AbstractType $left, AbstractType $right): AbstractType
    {
        if ($operator === BinaryOperator::Equal || $operator === BinaryOperator::NotEqual) {
            return self::resolveEqualityOperator($left, $right);
        }

        $leftUnwrap = Types\NullableType::unwrap($left);
        $rightUnwrap = Types\NullableType::unwrap($right);
        $nullable = $left !== $leftUnwrap || $right !== $rightUnwrap;

        if ($leftUnwrap instanceof Types\PrimitiveType && $rightUnwrap instanceof Types\PrimitiveType) {
            $leftPrimitive = $leftUnwrap->primitive;
            $rightPrimitive = $rightUnwrap->primitive;

            if ($operator === BinaryOperator::BitwiseAnd || $operator === BinaryOperator::BitwiseOr || $operator === BinaryOperator::BitwiseXor) {
                if ($leftPrimitive === Primitive::Bool && $rightPrimitive === Primitive::Bool) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Bool),
                        condition: $nullable,
                    );
                }

                if ($leftPrimitive === Primitive::Integer && $rightPrimitive === Primitive::Integer) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Integer),
                        condition: $nullable,
                    );
                }
            }

            if ($operator === BinaryOperator::BooleanAnd || $operator === BinaryOperator::BooleanOr) {
                if ($leftPrimitive === Primitive::Bool && $rightPrimitive === Primitive::Bool) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Bool),
                        condition: $nullable,
                    );
                }
            }

            if ($operator === BinaryOperator::Divide || $operator === BinaryOperator::Minus || $operator === BinaryOperator::Modulo || $operator === BinaryOperator::Multiply || $operator === BinaryOperator::Plus) {
                if ($leftPrimitive->isNumeric() && $rightPrimitive->isNumeric()) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType($leftPrimitive->promote($rightPrimitive)),
                        condition: $nullable,
                    );
                }

                if ($operator === BinaryOperator::Plus) {
                    if ($leftPrimitive === Primitive::String && $rightPrimitive === Primitive::String) {
                        return new Types\PrimitiveType(Primitive::String);
                    }
                }
            }

            if ($operator === BinaryOperator::GreaterThan || $operator === BinaryOperator::GreaterThanEqual || $operator === BinaryOperator::LessThan || $operator === BinaryOperator::LessThanEqual) {
                if ($leftPrimitive->isNumeric() && $rightPrimitive->isNumeric()) {
                    return new Types\PrimitiveType(Primitive::Bool);
                }

                if ($leftPrimitive === Primitive::String && $rightPrimitive === Primitive::String) {
                    return new Types\PrimitiveType(Primitive::Bool);
                }
            }

            if ($operator === BinaryOperator::ShiftLeft || $operator === BinaryOperator::ShiftRight) {
                if ($leftPrimitive === Primitive::Integer && $rightPrimitive === Primitive::Integer) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Integer),
                        condition: $nullable,
                    );
                }
            }

            if ($operator === BinaryOperator::Spaceship) {
                if ($leftPrimitive->isNumeric() && $rightPrimitive->isNumeric()) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Integer),
                        condition: $nullable,
                    );
                }

                if ($leftPrimitive === Primitive::String && $rightPrimitive === Primitive::String) {
                    return Types\NullableType::wrap(
                        type: new Types\PrimitiveType(Primitive::Integer),
                        condition: $nullable,
                    );
                }
            }
        }

        return new ErrorType;
    }

    private static function resolveEqualityOperator(AbstractType $left, AbstractType $right): AbstractType
    {
        if ($left instanceof Types\ArrayType && $right instanceof Types\ArrayType) {
            if (self::checkEqualTypes($left, $right)) {
                return new Types\PrimitiveType(Primitive::Bool);
            }
        }

        if ($left instanceof Types\PrimitiveType && $right instanceof Types\PrimitiveType) {
            if (self::checkEqualTypes($left, $right)) {
                return new Types\PrimitiveType(Primitive::Bool);
            }

            if ($left->primitive->isNumeric() && $right->primitive->isNumeric()) {
                return new Types\PrimitiveType(Primitive::Bool);
            }
        }

        if ($left instanceof Types\TupleType && $right instanceof Types\TupleType) {
            if (self::checkEqualTypes($left, $right)) {
                return new Types\PrimitiveType(Primitive::Bool);
            }
        }

        return new ErrorType;
    }

    private static function checkEqualTypes(AbstractType $left, AbstractType $right): bool
    {
        if ($left instanceof Types\ArrayType && $right instanceof Types\ArrayType) {
            if ($left->type === null || $right->type === null) {
                return $left->type === null && $right->type === null;
            }

            return self::checkEqualTypes($left->type, $right->type);
        }

        if ($left instanceof Types\NullableType || $right instanceof Types\NullableType) {
            if ($left instanceof Types\NullableType && $right instanceof Types\NullableType) {
                return self::checkEqualTypes($left->type, $right->type);
            }
        }

        if ($left instanceof Types\PrimitiveType && $right instanceof Types\PrimitiveType) {
            return $left->primitive === $right->primitive;
        }

        if ($left instanceof Types\TupleType && $right instanceof Types\TupleType) {
            if (count($left->types) !== count($right->types)) {
                return false;
            }

            foreach ($left->types as $index => $type) {
                if (!self::checkEqualTypes($type, $right->types[$index])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
