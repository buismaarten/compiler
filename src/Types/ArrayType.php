<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Types;

final class ArrayType extends AbstractType
{
    public ?AbstractType $type;

    public function __construct(?AbstractType $type)
    {
        $this->type = $type;
    }
}
