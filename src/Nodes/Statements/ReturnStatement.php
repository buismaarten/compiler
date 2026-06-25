<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class ReturnStatement extends AbstractStatement
{
    public ?AbstractExpression $expression;

    public function __construct(?AbstractExpression $expression = null, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->expression = $expression;
    }
}
