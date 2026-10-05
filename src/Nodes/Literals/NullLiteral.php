<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Override;
use Package\Common\Language\Primitive;
use Package\Common\Location;

/** @extends AbstractLiteral<null> */
final class NullLiteral extends AbstractLiteral
{
    public function __construct(Location $location)
    {
        parent::__construct($location);

        $this->value = null;
    }

    #[Override]
    public function getPrimitive(): Primitive
    {
        return Primitive::Null;
    }
}
