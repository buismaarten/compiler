<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractNode;

final class NumberNode extends AbstractNode
{
    public function __construct(
        public readonly int $value,
    ) {}
}
