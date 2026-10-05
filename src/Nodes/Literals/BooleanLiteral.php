<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Override;
use Package\Common\Language\Primitive;
use Package\Common\Location;

/** @extends AbstractLiteral<bool> */
final class BooleanLiteral extends AbstractLiteral
{
    public function __construct(Location $location, bool $value)
    {
        parent::__construct($location);

        $this->value = $value;
    }

    #[Override]
    public function getPrimitive(): Primitive
    {
        return Primitive::Bool;
    }
}
