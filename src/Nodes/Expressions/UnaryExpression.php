<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Common\Language\UnaryOperator;
use Package\Common\Location;

final class UnaryExpression extends AbstractExpression
{
    public bool $prefix;
    public UnaryOperator $operator;
    public AbstractExpression $expression;

    public function __construct(Location $location, bool $prefix, UnaryOperator $operator, AbstractExpression $expression)
    {
        parent::__construct($location);

        $this->prefix = $prefix;
        $this->operator = $operator;
        $this->expression = $expression;
    }
}
