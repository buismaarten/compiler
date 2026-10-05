<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\Primitive;
use Package\Common\Language\UnaryOperator;
use Package\Semantic\Types\ErrorType;
use Package\Types;
use Package\Types\AbstractType;

final class UnaryExpressionResolver
{
    public static function resolve(UnaryOperator $operator, AbstractType $type): AbstractType
    {
        if ($type instanceof Types\PrimitiveType) {
            if ($operator === UnaryOperator::BitwiseNot) {
                if ($type->primitive === Primitive::Integer) {
                    return new Types\PrimitiveType(Primitive::Integer);
                }
            }

            if ($operator === UnaryOperator::BooleanNot) {
                if ($type->primitive === Primitive::Bool) {
                    return new Types\PrimitiveType(Primitive::Bool);
                }
            }

            if ($operator === UnaryOperator::Decrement || $operator === UnaryOperator::Increment || $operator === UnaryOperator::Minus || $operator === UnaryOperator::Plus) {
                if ($type->primitive->isNumeric()) {
                    return new Types\PrimitiveType($type->primitive);
                }
            }
        }

        return new ErrorType;
    }
}
