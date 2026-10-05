<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

use Package\Common\Location;
use Package\Semantic\Types\AbstractType;

final class ValueSymbol extends AbstractSymbol
{
    public ValueSymbolKind $kind;

    public function __construct(Location $location, string $name, AbstractType $type, ValueSymbolKind $kind)
    {
        parent::__construct($location, $name, $type);

        $this->kind = $kind;
    }
}
