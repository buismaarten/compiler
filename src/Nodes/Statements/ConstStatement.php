<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\Expressions\IdentifierExpression;

final class ConstStatement extends AbstractStatement
{
    public readonly string $name;
    public readonly IdentifierExpression $type;
    public readonly AbstractExpression $expression;

    public function __construct(int $line, string $name, IdentifierExpression $type, AbstractExpression $expression)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->type = $type;
        $this->expression = $expression;
    }
}
