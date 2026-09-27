<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\ExpressionResolver;

use Package\Language\BinaryOperator;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;

final class BinaryExpressionResolver
{
    public static function resolve(BinaryOperator $operator, AbstractType $left, AbstractType $right): AbstractType
    {
        if ($left instanceof Types\ErrorType || $right instanceof Types\ErrorType) {
            return $left;
        }

        // TODO
        return $left;
    }
}
