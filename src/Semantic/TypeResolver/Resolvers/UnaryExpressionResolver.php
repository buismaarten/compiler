<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\UnaryOperator;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;

final class UnaryExpressionResolver
{
    public static function resolve(UnaryOperator $operator, AbstractType $type): AbstractType
    {
        if ($type instanceof Types\ArrayType) {
            // TODO
        }

        if ($type instanceof Types\NamedType) {
            // TODO
        }

        if ($type instanceof Types\NullableType) {
            // TODO
        }

        if ($type instanceof Types\PrimitiveType) {
            // TODO
        }

        if ($type instanceof Types\TupleType) {
            // TODO
        }

        return new Types\ErrorType;
    }
}
