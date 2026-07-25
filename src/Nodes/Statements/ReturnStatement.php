<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Core\Location;
use Package\Nodes\Expressions\AbstractExpression;

final class ReturnStatement extends AbstractStatement
{
    public ?AbstractExpression $expression;

    public function __construct(Location $location, ?AbstractExpression $expression = null)
    {
        parent::__construct($location);

        $this->expression = $expression;
    }
}
