<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Core\Location;
use Package\Language\BuiltinTypeKind;
use Package\Nodes\AbstractType;

final class BuiltinType extends AbstractType
{
    public BuiltinTypeKind $kind;

    public function __construct(Location $location, BuiltinTypeKind $kind)
    {
        parent::__construct($location);

        $this->kind = $kind;
    }
}
