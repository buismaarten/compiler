<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class PrintStatement extends AbstractStatement
{
    public readonly AbstractExpression $expression;

    public function __construct(int $line, AbstractExpression $expression)
    {
        parent::__construct($line);

        $this->expression = $expression;
    }
}
