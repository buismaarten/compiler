<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Common\Language;

enum UnaryOperator
{
    case BitwiseNot;
    case BooleanNot;
    case Decrement;
    case Increment;
    case Minus;
    case Plus;
}
