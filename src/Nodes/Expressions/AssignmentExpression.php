<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class AssignmentExpression extends AbstractExpression
{
    public readonly string $name;
    public readonly AbstractExpression $expression;

    public function __construct(int $line, string $name, AbstractExpression $expression)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->expression = $expression;
    }
}
