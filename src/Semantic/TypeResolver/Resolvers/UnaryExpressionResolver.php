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
        if ($type instanceof Types\PrimitiveType) {
            if ($operator === UnaryOperator::BitwiseNot) {
                if ($type->primitive === Primitive::Integer) {
                    return $type;
                }
            }

            if ($operator === UnaryOperator::BooleanNot) {
                if ($type->primitive === Primitive::Bool) {
                    return $type;
                }
            }

            if ($operator === UnaryOperator::Decrement || $operator === UnaryOperator::Increment || $operator === UnaryOperator::Minus || $operator === UnaryOperator::Plus) {
                if ($type->primitive->isNumeric()) {
                    return $type;
                }
            }
        }

        return new Types\ErrorType;
    }
}
