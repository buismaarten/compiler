<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class ContinueStatement extends AbstractStatement
{
    public ?AbstractExpression $level;

    public function __construct(int $line, ?AbstractExpression $level = null)
    {
        parent::__construct($line);

        $this->level = $level;
    }
}
