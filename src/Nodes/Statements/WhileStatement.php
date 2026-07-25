<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Core\Location;
use Package\Nodes\Block;
use Package\Nodes\Expressions\AbstractExpression;

final class WhileStatement extends AbstractStatement
{
    public AbstractExpression $condition;
    public Block $statements;

    public function __construct(Location $location, AbstractExpression $condition, Block $statements)
    {
        parent::__construct($location);

        $this->condition = $condition;
        $this->statements = $statements;
    }
}
