<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Package\Nodes\AbstractNode;
use Package\Semantic\Symbols\AbstractSymbol;
use WeakMap;

final class TypeChecker
{
    /** @var WeakMap<AbstractNode, AbstractSymbol> */
    private WeakMap $mapping;

    /** @param WeakMap<AbstractNode, AbstractSymbol> $mapping */
    public function __construct(WeakMap $mapping)
    {
        $this->mapping = $mapping;
    }

    public function validate(): void
    {
        foreach ($this->mapping as $node => $symbol) {
            // TODO
        }
    }
}
