<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\ExpressionResolver;

use Package\Language\UnaryOperator;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;

final class UnaryExpressionResolver
{
    public static function resolve(UnaryOperator $operator, AbstractType $type): AbstractType
    {
        if ($type instanceof Types\ErrorType) {
            return $type;
        }

        // TODO
        return $type;
    }
}
