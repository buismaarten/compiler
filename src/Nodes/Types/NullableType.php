<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Core\Location;
use Package\Nodes\AbstractType;

final class NullableType extends AbstractType
{
    public AbstractType $type;

    public function __construct(Location $location, AbstractType $type)
    {
        parent::__construct($location);

        $this->type = $type;
    }
}
