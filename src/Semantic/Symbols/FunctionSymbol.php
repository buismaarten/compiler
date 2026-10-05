<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

final class FunctionSymbol extends AbstractSymbol
{
    /** @var ValueSymbol[] */
    public array $parameters = [];
}
