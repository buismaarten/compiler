<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Types\AbstractType;
use WeakMap;

final class TypeResolver
{
    private AbstractNode $node;
    private SymbolResolver $symbolResolver;

    /** @var WeakMap<AbstractNode, AbstractType>|null */
    private ?WeakMap $table = null;

    public function __construct(AbstractNode $node, SymbolResolver $symbolResolver)
    {
        $this->node = $node;
        $this->symbolResolver = $symbolResolver;
    }

    /** @return WeakMap<AbstractNode, AbstractType> */
    public function getTable(): WeakMap
    {
        if ($this->table === null) {
            $this->table = new WeakMap;

            $traverser = new NodeTraverser;
            $traverser->addVisitor(new TypeNodeVisitor($this->symbolResolver, $this->table));
            $traverser->traverse($this->node);
        }

        return $this->table;
    }

    public function resolve(AbstractNode $node): AbstractType
    {
        return $this->getTable()[$node];
    }
}
