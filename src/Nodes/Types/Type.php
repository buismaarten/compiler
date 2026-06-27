<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

enum Type: string
{
    case Bool = 'bool';
    case Float = 'float';
    case Integer = 'int';
    case String = 'string';
    case Void = 'void';
}
