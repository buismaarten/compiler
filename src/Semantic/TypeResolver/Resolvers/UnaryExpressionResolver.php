<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\Primitive;
use Package\Common\Language\UnaryOperator;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;

final class UnaryExpressionResolver
{
    public static function resolve(UnaryOperator $operator, AbstractType $type): AbstractType
    {
        if ($type instanceof Types\NullableType) {
            return self::resolveNullableType($operator, $type);
        }

        if ($type instanceof Types\PrimitiveType) {
            return self::resolvePrimitiveType($operator, $type);
        }

        return new Types\ErrorType;
    }

    private static function resolveNullableType(UnaryOperator $operator, Types\NullableType $type): AbstractType
    {
        $type = self::resolve($operator, $type->type);

        if ($type instanceof Types\ErrorType) {
            return $type;
        }

        return new Types\NullableType($type);
    }

    private static function resolvePrimitiveType(UnaryOperator $operator, Types\PrimitiveType $type): AbstractType
    {
        if ($type->type === Primitive::Bool) {
            if (in_array($operator, [UnaryOperator::BooleanNot])) {
                return $type;
            }
        }

        if ($type->type === Primitive::Float) {
            if (in_array($operator, [UnaryOperator::Decrement, UnaryOperator::Increment, UnaryOperator::Minus, UnaryOperator::Plus])) {
                return $type;
            }
        }

        if ($type->type === Primitive::Integer) {
            if (in_array($operator, [UnaryOperator::BitwiseNot, UnaryOperator::Decrement, UnaryOperator::Increment, UnaryOperator::Minus, UnaryOperator::Plus])) {
                return $type;
            }
        }

        return new Types\ErrorType;
    }
}
