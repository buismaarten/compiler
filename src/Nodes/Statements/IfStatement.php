<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Common\Location;
use Package\Nodes\Block;
use Package\Nodes\Expressions\AbstractExpression;

final class IfStatement extends AbstractStatement
{
    public AbstractExpression $condition;
    public Block $thenStatements;
    public ?Block $elseStatements;

    public function __construct(Location $location, AbstractExpression $condition, Block $thenStatements, ?Block $elseStatements = null)
    {
        parent::__construct($location);

        $this->condition = $condition;
        $this->thenStatements = $thenStatements;
        $this->elseStatements = $elseStatements;
    }
}
