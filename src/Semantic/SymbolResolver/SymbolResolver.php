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
    /** @var WeakMap<AbstractNode, AbstractSymbol> */
    private WeakMap $symbols;

    public function __construct(AbstractNode $node)
    {
        $this->symbols = new WeakMap;

        $this->traverse($node);
    }

    /** @return WeakMap<AbstractNode, AbstractSymbol> */
    public function getSymbols(): WeakMap
    {
        return $this->symbols;
    }

    public function resolveSymbol(AbstractNode $node): AbstractSymbol
    {
        return $this->symbols[$node];
    }

    private function traverse(AbstractNode $node): void
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new SymbolNodeVisitor($this->symbols));
        $traverser->traverse($node);
    }
}
