<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

use Package\Core\Location;

abstract class AbstractSymbol
{
    public readonly Location $location;
    public readonly string $name;

    public function __construct(Location $location, string $name)
    {
        $this->location = $location;
        $this->name = $name;
    }
}
