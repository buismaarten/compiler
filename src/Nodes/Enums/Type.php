<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Enums;

enum Type: string
{
    case Bool = 'bool';
    case Float = 'float';
    case Int = 'int';
    case String = 'string';
    case Void = 'void';
}
