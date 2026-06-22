<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class UnaryExpression extends AbstractExpression
{
    public UnaryPosition $position;
    public UnaryOperator $operator;
    public AbstractExpression $expression;

    public function __construct(int $line, UnaryPosition $position, UnaryOperator $operator, AbstractExpression $expression)
    {
        parent::__construct($line);

        $this->position = $position;
        $this->operator = $operator;
        $this->expression = $expression;
    }
}
