<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;

abstract class AbstractNodeVisitor implements NodeVisitor
{
    /** {@inheritDoc} */
    public function beforeTraverse(array $nodes)
    {
        return null;
    }

    /** {@inheritDoc} */
    public function enterNode(AbstractNode $node)
    {
        return null;
    }

    /** {@inheritDoc} */
    public function leaveNode(AbstractNode $node)
    {
        return null;
    }

    /** {@inheritDoc} */
    public function afterTraverse(array $nodes)
    {
        return null;
    }
}
