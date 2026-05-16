<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Nodes;

use Buismaarten\Parser\Token\TokenType;

final class BinaryNode implements NodeInterface
{
    public function __construct(
        public readonly TokenType $operator,
        public readonly NodeInterface $left,
        public readonly NodeInterface $right,
    ) {}
}
