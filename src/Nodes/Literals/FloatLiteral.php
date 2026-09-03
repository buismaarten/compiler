<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Core\Location;
use Package\Language\BuiltinTypeKind;

final class FloatLiteral extends AbstractLiteral
{
    public float $value;

    public function __construct(Location $location, float $value)
    {
        parent::__construct($location);

        $this->value = $value;
    }

    public function getBuiltinTypeKind(): BuiltinTypeKind
    {
        return BuiltinTypeKind::Float;
    }
}
