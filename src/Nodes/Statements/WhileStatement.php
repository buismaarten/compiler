<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class WhileStatement extends AbstractStatement
{
    public readonly AbstractExpression $condition;

    /** @var AbstractStatement[] */
    public readonly array $statements;

    /** @param AbstractStatement[] $statements */
    public function __construct(int $line, AbstractExpression $condition, array $statements)
    {
        parent::__construct($line);

        $this->condition = $condition;
        $this->statements = $statements;
    }
}
