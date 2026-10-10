<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\BinaryOperator;
use Package\Common\Language\Primitive;
use Package\Types;
use Package\Types\AbstractType;

final class BinaryExpressionResolver
{
    public static function resolve(BinaryOperator $operator, AbstractType $left, AbstractType $right): AbstractType
    {
        if ($operator === BinaryOperator::Equal || $operator === BinaryOperator::NotEqual) {
            if (self::checkTypes($left, $right)) {
                return new Types\PrimitiveType(Primitive::Bool);
            }
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

        return new Types\ErrorType;
    }

    private static function checkTypes(AbstractType $left, AbstractType $right): bool
    {
        if ($left instanceof Types\ArrayType && $right instanceof Types\ArrayType) {
            if ($left->type !== null && $right->type !== null) {
                return self::checkTypes($left->type, $right->type);
            }

            return true;
        }

        if ($left instanceof Types\NullableType || $right instanceof Types\NullableType) {
            if (self::checkNullableTypes($left, $right) || self::checkNullableTypes($right, $left)) {
                return true;
            }

            return self::checkTypes(
                left: Types\NullableType::unwrap($left),
                right: Types\NullableType::unwrap($right),
            );
        }

        if ($left instanceof Types\PrimitiveType && $right instanceof Types\PrimitiveType) {
            return $left->primitive->promote($right->primitive) === $right->primitive->promote($left->primitive);
        }

        if ($left instanceof Types\TupleType && $right instanceof Types\TupleType) {
            if (count($left->types) !== count($right->types)) {
                return false;
            }

            foreach ($left->types as $index => $type) {
                if (!self::checkTypes($type, $right->types[$index])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    private static function checkNullableTypes(AbstractType $left, AbstractType $right): bool
    {
        if ($left instanceof Types\NullableType && $right instanceof Types\PrimitiveType) {
            return $right->primitive->isNullable();
        }

        return false;
    }
}
