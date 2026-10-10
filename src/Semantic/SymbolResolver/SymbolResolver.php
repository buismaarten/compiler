<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\SymbolResolver;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\Symbols\AbstractSymbol;
use SplObjectStorage;

final class SymbolResolver
{
    /** @var SplObjectStorage<AbstractNode, AbstractSymbol> */
    private SplObjectStorage $symbols;

    public function __construct(AbstractNode $node)
    {
        $this->symbols = new SplObjectStorage;

        $this->traverse($node);
    }

    /** @return SplObjectStorage<AbstractNode, AbstractSymbol> */
    public function getSymbols(): SplObjectStorage
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
        $traverser->addVisitor(new SymbolResolverNodeVisitor($this->symbols));
        $traverser->traverse($node);
    }
}
