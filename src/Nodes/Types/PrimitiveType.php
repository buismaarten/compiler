<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Common\Language\Primitive;
use Package\Common\Location;

final class PrimitiveType extends AbstractType
{
    public Primitive $primitive;

    public function __construct(Location $location, Primitive $primitive)
    {
        parent::__construct($location);

        $this->primitive = $primitive;
    }
}
