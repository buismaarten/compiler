<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

final class BinaryNode extends AbstractNode
{
    public function __construct(
        public readonly string $operator,
        public readonly AbstractNode $left,
        public readonly AbstractNode $right,
    ) {}
}
