<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

use Package\Common\Language\Primitive;

final class PrimitiveType extends AbstractType
{
    public Primitive $primitive;

    public function __construct(Primitive $primitive)
    {
        $this->primitive = $primitive;
    }
}
