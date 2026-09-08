<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Core\Location;
use Package\Language\Primitive;

final class StringLiteral extends AbstractLiteral
{
    public string $value;

    public function __construct(Location $location, string $value)
    {
        parent::__construct($location);

        $this->value = $value;
    }

    public function getPrimitive(): Primitive
    {
        return Primitive::String;
    }
}
