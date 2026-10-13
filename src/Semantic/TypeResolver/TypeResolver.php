<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Symbols\AbstractSymbol;
use Package\Types\AbstractType;
use SplObjectStorage;

final class TypeResolver
{
    private SymbolResolver $symbolResolver;

    /** @var SplObjectStorage<AbstractNode, AbstractType> */
    private SplObjectStorage $types;

    /** @var SplObjectStorage<AbstractSymbol, AbstractType[]> */
    private SplObjectStorage $functionReturnTypes;

    public function __construct(AbstractNode $node, ?SymbolResolver $symbolResolver = null)
    {
        $this->types = new SplObjectStorage;
        $this->functionReturnTypes = new SplObjectStorage;

        $this->traverse($node, $symbolResolver);
    }

    /** @return SplObjectStorage<AbstractSymbol, AbstractType[]> */
    public function getFunctionReturnTypes(): SplObjectStorage
    {
        return $this->functionReturnTypes;
    }

    /** @return SplObjectStorage<AbstractNode, AbstractType> */
    public function getTypes(): SplObjectStorage
    {
        return $this->types;
    }

    public function getSymbolResolver(): SymbolResolver
    {
        return $this->symbolResolver;
    }

    /** @return AbstractType[] */
    public function resolveFunctionReturnTypes(AbstractSymbol $node): array
    {
        return $this->functionReturnTypes[$node];
    }

    public function resolveType(AbstractNode $node): AbstractType
    {
        return $this->types[$node];
    }

    private function traverse(AbstractNode $node, ?SymbolResolver $symbolResolver): void
    {
        if ($symbolResolver === null) {
            $symbolResolver = new SymbolResolver($node);
        }

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new TypeResolverNodeVisitor($symbolResolver, $this->types, $this->functionReturnTypes));
        $traverser->traverse($node);
    }
}
