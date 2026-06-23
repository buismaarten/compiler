<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Override;
use Package\Nodes\AbstractNode;

abstract class AbstractNodeVisitor implements NodeVisitor
{
    /** {@inheritDoc} */
    #[Override]
    public function beforeTraverse(array $nodes)
    {
        return null;
    }

    /** {@inheritDoc} */
    #[Override]
    public function enterNode(AbstractNode $node)
    {
        return null;
    }

    /** {@inheritDoc} */
    #[Override]
    public function leaveNode(AbstractNode $node)
    {
        return null;
    }

    /** {@inheritDoc} */
    #[Override]
    public function afterTraverse(array $nodes)
    {
        return null;
    }
}
