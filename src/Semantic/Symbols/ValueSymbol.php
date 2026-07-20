<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

use Package\Core\Location;

final class ValueSymbol extends AbstractSymbol
{
    public readonly ValueSymbolKind $kind;

    public function __construct(Location $location, string $name, ValueSymbolKind $kind)
    {
        parent::__construct($location, $name);

        $this->kind = $kind;
    }
}
