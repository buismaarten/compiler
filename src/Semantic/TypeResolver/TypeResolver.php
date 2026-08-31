<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeResolver;

use Package\Nodes\AbstractNode;

// TODO
final class TypeResolver
{
    private AbstractNode $node;

    public function __construct(AbstractNode $node)
    {
        $this->node = $node;
    }
}
