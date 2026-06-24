<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class BinaryExpression extends AbstractExpression
{
    public BinaryOperator $operator;
    public AbstractExpression $left;
    public AbstractExpression $right;

    public function __construct(int $line, BinaryOperator $operator, AbstractExpression $left, AbstractExpression $right)
    {
        parent::__construct($line);

        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }
}
