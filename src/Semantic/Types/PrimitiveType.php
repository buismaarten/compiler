<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

use Package\Language\Primitive;

final class PrimitiveType extends AbstractType
{
    public Primitive $type;

    public function __construct(Primitive $type)
    {
        $this->type = $type;
    }
}
