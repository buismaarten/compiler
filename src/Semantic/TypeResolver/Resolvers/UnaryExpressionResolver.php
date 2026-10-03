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
        if ($operator === UnaryOperator::BitwiseNot) {
            if ($type instanceof Types\PrimitiveType && $type->type === Primitive::Integer) {
                return $type;
            }
        }

        if ($operator === UnaryOperator::BooleanNot) {
            if ($type instanceof Types\PrimitiveType && $type->type === Primitive::Bool) {
                return $type;
            }
        }

        if ($operator === UnaryOperator::Decrement || $operator === UnaryOperator::Increment) {
            if ($type instanceof Types\PrimitiveType && ($type->type === Primitive::Integer || $type->type === Primitive::Float)) {
                return $type;
            }
        }

        if ($operator === UnaryOperator::Minus || $operator === UnaryOperator::Plus) {
            if ($type instanceof Types\PrimitiveType && ($type->type === Primitive::Integer || $type->type === Primitive::Float)) {
                return $type;
            }
        }

        return new Types\ErrorType;
    }
}
