<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Override;
use Package\Nodes\AbstractNode;

abstract class AbstractNodeVisitor implements NodeVisitor
{
    #[Override]
    public function beforeTraverse(AbstractNode $node)
    {
        return null;
    }

    #[Override]
    public function enterNode(AbstractNode $node)
    {
        return null;
    }

    #[Override]
    public function leaveNode(AbstractNode $node)
    {
        return null;
    }

    #[Override]
    public function afterTraverse(AbstractNode $node)
    {
        return null;
    }
}
