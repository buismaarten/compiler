<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Common\Location;
use Package\Nodes\Expressions\AbstractExpression;

final class PrintStatement extends AbstractStatement
{
    public AbstractExpression $expression;

    public function __construct(Location $location, AbstractExpression $expression)
    {
        parent::__construct($location);

        $this->expression = $expression;
    }
}
