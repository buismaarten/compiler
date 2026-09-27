<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Override;
use Package\Common\Location;
use Package\Language\Primitive;

/** @extends AbstractLiteral<float> */
final class FloatLiteral extends AbstractLiteral
{
    public function __construct(Location $location, float $value)
    {
        parent::__construct($location);

        $this->value = $value;
    }

    #[Override]
    public function getPrimitive(): Primitive
    {
        return Primitive::Float;
    }
}
