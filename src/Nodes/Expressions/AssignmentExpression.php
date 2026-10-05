<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Common\Location;

final class AssignmentExpression extends AbstractExpression
{
    public string $name;
    public AbstractExpression $expression;

    public function __construct(Location $location, string $name, AbstractExpression $expression)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->expression = $expression;
    }
}
