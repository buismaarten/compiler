<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Common\Language;

enum Primitive: string
{
    case Bool = 'bool';
    case Float = 'float';
    case Integer = 'int';
    case Null = 'null';
    case String = 'string';
    case Void = 'void';

    public function isNumeric(): bool
    {
        if ($this === self::Float || $this === self::Integer) {
            return true;
        }

        return false;
    }

    public function promote(self $other): self
    {
        if ($this === self::Void || $other === self::Void) {
            return self::Null;
        }

        if ($this === self::Float || $other === self::Float) {
            return self::Float;
        }

        return $this;
    }
}
