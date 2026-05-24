<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class PrintStatement extends AbstractStatement
{
    public function __construct(
        public readonly int $line,
        public readonly AbstractExpression $expression,
    ) {}
}
