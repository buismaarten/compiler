<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;
use Package\Nodes\Location;

final class UnaryExpression extends AbstractExpression
{
    public UnaryOperator $operator;
    public UnaryPosition $position;
    public AbstractExpression $expression;

    public function __construct(Location $location, UnaryOperator $operator, UnaryPosition $position, AbstractExpression $expression)
    {
        parent::__construct($location);

        $this->operator = $operator;
        $this->position = $position;
        $this->expression = $expression;
    }
}
