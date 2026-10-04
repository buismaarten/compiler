<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\BinaryOperator;
use Package\Common\Language\Primitive;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;

final class BinaryExpressionResolver
{
    // TODO
    public static function resolve(BinaryOperator $operator, AbstractType $left, AbstractType $right): AbstractType
    {
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

            if ($operator === BinaryOperator::Divide || $operator === BinaryOperator::Minus || $operator === BinaryOperator::Multiply || $operator === BinaryOperator::Plus) {
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

            if ($operator === BinaryOperator::ShiftLeft || $operator === BinaryOperator::ShiftRight) {
                if ($leftPrimitive === Primitive::Integer && $rightPrimitive === Primitive::Integer && !$nullable) {
                    return new Types\PrimitiveType(Primitive::Integer);
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
}
