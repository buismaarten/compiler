<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Override;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\Types\AbstractType;
use WeakMap;

final class TypeNodeVisitor extends AbstractNodeVisitor
{
    private SymbolResolver $symbolResolver;

    /** @var WeakMap<AbstractNode, AbstractType> */
    private WeakMap $table;

    /** @param WeakMap<AbstractNode, AbstractType> $table */
    public function __construct(SymbolResolver $symbolResolver, WeakMap $table)
    {
        $this->symbolResolver = $symbolResolver;
        $this->table = $table;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        // TODO
        unset($this->symbolResolver);
        unset($this->table);
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        // TODO
        unset($this->symbolResolver);
        unset($this->table);
    }
}
