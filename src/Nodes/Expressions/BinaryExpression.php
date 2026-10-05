<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Common\Language\BinaryOperator;
use Package\Common\Location;

final class BinaryExpression extends AbstractExpression
{
    public BinaryOperator $operator;
    public AbstractExpression $left;
    public AbstractExpression $right;

    public function __construct(Location $location, BinaryOperator $operator, AbstractExpression $left, AbstractExpression $right)
    {
        parent::__construct($location);

        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }
}
