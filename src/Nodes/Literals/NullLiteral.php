<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractLiteral;
use Package\Nodes\Location;

final class NullLiteral extends AbstractLiteral
{
    public null $value;

    public function __construct(Location $location, null $value = null)
    {
        parent::__construct($location);

        $this->value = $value;
    }
}
