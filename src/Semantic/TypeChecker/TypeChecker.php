<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\TypeResolver\TypeResolver;

final class TypeChecker
{
    public function __construct(AbstractNode $node, ?TypeResolver $typeResolver = null)
    {
        $this->traverse($node, $typeResolver);
    }

    private function traverse(AbstractNode $node, ?TypeResolver $typeResolver): void
    {
        if ($typeResolver === null) {
            $typeResolver = new TypeResolver($node);
        }

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new TypeNodeVisitor($typeResolver));
        $traverser->traverse($node);
    }
}
