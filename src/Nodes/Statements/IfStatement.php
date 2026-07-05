<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;
use Package\Nodes\Location;

final class IfStatement extends AbstractStatement
{
    public AbstractExpression $condition;

    /** @var AbstractStatement[] */
    public array $thenStatements;

    /** @var AbstractStatement[]|null */
    public ?array $elseStatements;

    /**
     * @param AbstractStatement[] $thenStatements
     * @param AbstractStatement[]|null $elseStatements
     */
    public function __construct(Location $location, AbstractExpression $condition, array $thenStatements, ?array $elseStatements = null)
    {
        parent::__construct($location);

        $this->condition = $condition;
        $this->thenStatements = $thenStatements;
        $this->elseStatements = $elseStatements;
    }
}
