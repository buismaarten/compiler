<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;
use Package\Nodes\Enums\Operator;

final class BinaryNode extends AbstractExpression
{
    public function __construct(
        public readonly Operator $operator,
        public readonly AbstractExpression $left,
        public readonly AbstractExpression $right,
    ) {}
}
