<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Common\Location;

final class NamedType extends AbstractType
{
    public string $name;

    public function __construct(Location $location, string $name)
    {
        parent::__construct($location);

        $this->name = $name;
    }
}
