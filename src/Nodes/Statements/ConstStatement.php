<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class ConstStatement extends AbstractStatement
{
    public function __construct(
        public readonly string $name,
        public readonly AbstractExpression $value,
    ) {}
}
