<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\Location;

final class WhileStatement extends AbstractStatement
{
    public AbstractExpression $condition;

    /** @var AbstractStatement[] */
    public array $statements;

    /** @param AbstractStatement[] $statements */
    public function __construct(Location $location, AbstractExpression $condition, array $statements)
    {
        parent::__construct($location);

        $this->condition = $condition;
        $this->statements = $statements;
    }
}
