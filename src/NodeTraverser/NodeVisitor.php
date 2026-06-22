<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;

interface NodeVisitor
{
    public function visit(AbstractNode $node): AbstractNode;
}
