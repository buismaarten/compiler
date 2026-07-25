<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

use Package\Core\Location;
use Package\Semantic\Types\AbstractType;

abstract class AbstractSymbol
{
    public Location $location;
    public string $name;
    public AbstractType $type;

    public function __construct(Location $location, string $name, AbstractType $type)
    {
        $this->location = $location;
        $this->name = $name;
        $this->type = $type;
    }
}
