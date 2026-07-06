<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;

interface NodeVisitor
{
    /** @return AbstractNode|null|void */
    public function beforeTraverse(AbstractNode $nodes);

    /** @return AbstractNode|null|void */
    public function enterNode(AbstractNode $node);

    /** @return AbstractNode|null|void */
    public function leaveNode(AbstractNode $node);

    /** @return AbstractNode|null|void */
    public function afterTraverse(AbstractNode $nodes);
}
