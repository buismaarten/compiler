<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class IfStatement extends AbstractStatement
{
    public function __construct(
        public readonly AbstractExpression $condition,

        /** @var AbstractStatement[] */
        public readonly array $thenStatements,

        /** @var AbstractStatement[] */
        public readonly array $elseStatements = [],
    ) {}
}
