<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeValidator;

use Package\Nodes\AbstractNode;
use Package\NodeTraverser\NodeTraverser;
use Package\Semantic\TypeResolver\TypeResolver;

final class TypeValidator
{
    private TypeResolver $typeResolver;

    public function __construct(AbstractNode $node, ?TypeResolver $typeResolver = null)
    {
        $this->typeResolver = $typeResolver ?? new TypeResolver($node);

        $this->traverse($node);
    }

    public function getTypeResolver(): TypeResolver
    {
        return $this->typeResolver;
    }

    private function traverse(AbstractNode $node): void
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new TypeValidatorNodeVisitor($this->typeResolver));
        $traverser->traverse($node);
    }
}
