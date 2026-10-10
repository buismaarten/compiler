<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Override;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\TypeResolver\TypeResolver;

final class TypeCheckerNodeVisitor extends AbstractNodeVisitor
{
    private TypeResolver $typeResolver;

    public function __construct(TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        // TODO
        var_dump($this->typeResolver->getTypes());
    }
}
