<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

final class NullableType extends AbstractType
{
    public AbstractType $type;

    public function __construct(AbstractType $type)
    {
        $this->type = $type;
    }

    public static function unwrap(AbstractType $type): AbstractType
    {
        if ($type instanceof self) {
            return $type->type;
        }

        return $type;
    }

    public static function wrap(AbstractType $type, bool $condition = true): AbstractType
    {
        if ($condition && !($type instanceof self)) {
            return new self($type);
        }

        return $type;
    }
}
