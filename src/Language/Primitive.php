<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Language;

enum Primitive: string
{
    case Boolean = 'bool';
    case Float = 'float';
    case Integer = 'int';
    case Null = 'null';
    case String = 'string';
    case Void = 'void';
}
