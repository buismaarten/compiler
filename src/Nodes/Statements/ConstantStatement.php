<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Core\Location;
use Package\Nodes\Expressions\AbstractExpression;
use Package\Nodes\Types\AbstractType;

final class ConstantStatement extends AbstractStatement
{
    public string $name;
    public AbstractType $type;
    public AbstractExpression $expression;

    public function __construct(Location $location, string $name, AbstractType $type, AbstractExpression $expression)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->type = $type;
        $this->expression = $expression;
    }
}
