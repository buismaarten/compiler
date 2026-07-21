<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\SymbolResolver;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\Symbols\AbstractSymbol;
use WeakMap;

final class SymbolResolver
{
    private AbstractNode $root;

    /** @var WeakMap<AbstractNode, AbstractSymbol>|null */
    private ?WeakMap $table = null;

    public function __construct(AbstractNode $root)
    {
        $this->root = $root;
    }

<<<<<<< HEAD
    /** @return WeakMap<AbstractNode, AbstractSymbol> */
=======
    /** @return WeakMap<AbstractNode, AbstractNode> */
>>>>>>> main
    public function getTable(): WeakMap
    {
        if ($this->table === null) {
            $this->table = new WeakMap;

            $traverser = new NodeTraverser;
            $traverser->addVisitor(new SymbolNodeVisitor($this->table));
            $traverser->traverse($this->root);
        }

        return $this->table;
    }

<<<<<<< HEAD
    public function resolve(AbstractNode $node): ?AbstractSymbol
=======
    public function resolve(AbstractNode $node): ?AbstractNode
>>>>>>> main
    {
        return $this->getTable()[$node] ?? null;
    }
}
