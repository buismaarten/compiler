<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Nodes\AbstractType;
use Package\Nodes\Location;

final class BuiltinType extends AbstractType
{
    public Type $type;

    public function __construct(Location $location, Type $type)
    {
        parent::__construct($location);

        $this->type = $type;
    }
}
