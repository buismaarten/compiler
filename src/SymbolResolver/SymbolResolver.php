<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\SymbolResolver;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use WeakMap;

final class SymbolResolver
{
    private AbstractNode $root;

    /** @var WeakMap<AbstractNode, AbstractNode>|null */
    private ?WeakMap $table = null;

    public function __construct(AbstractNode $root)
    {
        $this->root = $root;
    }

    public function resolve(AbstractNode $node): ?AbstractNode
    {
        if ($this->table === null) {
            $this->table = new WeakMap;

            $traverser = new NodeTraverser;
            $traverser->addVisitor(new SymbolNodeVisitor($this->table));
            $traverser->traverse($this->root);
        }

        return $this->table[$node] ?? null;
    }
}
