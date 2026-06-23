<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;

interface NodeVisitor
{
    /**
     * @param AbstractNode[] $nodes
     * @return AbstractNode[]|void
     */
    public function beforeTraverse(array $nodes);

    /**
     * @param AbstractNode $node
     * @return AbstractNode|void
     */
    public function enterNode(AbstractNode $node);

    /**
     * @param AbstractNode $node
     * @return AbstractNode|void
     */
    public function leaveNode(AbstractNode $node);

    /**
     * @param AbstractNode[] $nodes
     * @return AbstractNode[]|void
     */
    public function afterTraverse(array $nodes);
}
