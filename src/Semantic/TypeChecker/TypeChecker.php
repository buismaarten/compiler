<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Package\Nodes\AbstractNode;
use Package\Semantic\Symbols\AbstractSymbol;

final class TypeChecker
{
    /** @var iterable<AbstractNode, AbstractSymbol> */
    private iterable $mapping;

    /** @param iterable<AbstractNode, AbstractSymbol> $mapping */
    public function __construct(iterable $mapping)
    {
        $this->mapping = $mapping;
    }

    public function check(): void
    {
        foreach ($this->mapping as $node => $symbol) {
            //
        }
    }
}
