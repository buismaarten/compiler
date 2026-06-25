<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\AbstractType;

final class ConstStatement extends AbstractStatement
{
    public string $name;
    public AbstractType $type;
    public AbstractExpression $expression;

    public function __construct(string $name, AbstractType $type, AbstractExpression $expression, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $name;
        $this->type = $type;
        $this->expression = $expression;
    }
}
