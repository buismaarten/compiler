<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\NodeTraverser\NodeTraverser;
use SplObjectStorage;

final class SymbolResolver
{
    private Block $root;

    /** @var SplObjectStorage<AbstractNode, AbstractNode>|null */
    private ?SplObjectStorage $table = null;

    public function __construct(Block $root)
    {
        $this->root = $root;
    }

    public function resolve(AbstractNode $node): ?AbstractNode
    {
        if ($this->table === null) {
            $this->table = new SplObjectStorage;

            $traverser = new NodeTraverser;
            $traverser->addVisitor(new SymbolCollector($this->table));
            $traverser->traverse($this->root);
        }

        return $this->table[$node] ?? null;
    }
}
