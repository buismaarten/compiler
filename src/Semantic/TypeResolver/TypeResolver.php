<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Package\Nodes\AbstractNode;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Types\AbstractType;
use WeakMap;

// TODO
final class TypeResolver
{
    private AbstractNode $node;
    private SymbolResolver $symbolResolver;

    /** @var WeakMap<AbstractNode, AbstractType> */
    private WeakMap $table;

    public function __construct(AbstractNode $node, SymbolResolver $symbolResolver)
    {
        $this->node = $node;
        $this->symbolResolver = $symbolResolver;
    }
}
