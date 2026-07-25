<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Core\Location;
use Package\Nodes\Types\AbstractType;

final class Parameter extends AbstractNode
{
    public string $name;
    public AbstractType $type;

    public function __construct(Location $location, string $name, AbstractType $type)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->type = $type;
    }
}
