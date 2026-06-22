<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

final class IfStatement extends AbstractStatement
{
    public AbstractExpression $condition;

    /** @var AbstractStatement[] */
    public array $thenStatements;

    /** @var AbstractStatement[] */
    public array $elseStatements;

    /**
     * @param AbstractStatement[] $thenStatements
     * @param AbstractStatement[] $elseStatements
     */
    public function __construct(int $line, AbstractExpression $condition, array $thenStatements, array $elseStatements = [])
    {
        parent::__construct($line);

        $this->condition = $condition;
        $this->thenStatements = $thenStatements;
        $this->elseStatements = $elseStatements;
    }
}
