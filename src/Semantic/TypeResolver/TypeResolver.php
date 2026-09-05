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

    /** @var WeakMap<AbstractNode, AbstractType> */
    private WeakMap $types;

    /** @var WeakMap<AbstractNode, AbstractType[]> */
    private WeakMap $functionReturnTypes;

    public function __construct(AbstractNode $node, SymbolResolver $symbolResolver)
    {
        $this->node = $node;
        $this->symbolResolver = $symbolResolver;
        $this->types = new WeakMap;
        $this->functionReturnTypes = new WeakMap;

        $this->traverse();
    }

    /** @return WeakMap<AbstractNode, AbstractType[]> */
    public function getFunctionReturnTypes(): WeakMap
    {
        return $this->functionReturnTypes;
    }

    /** @return WeakMap<AbstractNode, AbstractType> */
    public function getTypes(): WeakMap
    {
        return $this->types;
    }

    /** @return AbstractType[] */
    public function resolveFunctionReturnTypes(AbstractNode $node): array
    {
        return $this->functionReturnTypes[$node];
    }

    public function resolveType(AbstractNode $node): AbstractType
    {
        return $this->types[$node];
    }

    private function traverse(): void
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new TypeNodeVisitor($this->symbolResolver, $this->types, $this->functionReturnTypes));
        $traverser->traverse($this->node);
    }
}
