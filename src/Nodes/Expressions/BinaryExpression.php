<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class BinaryExpression extends AbstractExpression
{
    public readonly AbstractExpression $left;
    public readonly BinaryOperator $operator;
    public readonly AbstractExpression $right;

    public function __construct(int $line, AbstractExpression $left, BinaryOperator $operator, AbstractExpression $right)
    {
        parent::__construct($line);

        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
}
