<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractStatement;
use Package\Nodes\Parameter;

final class FunctionStatement extends AbstractStatement
{
    public readonly string $name;

    /** @var Parameter[] */
    public readonly array $parameters;

    /** @var AbstractStatement[] */
    public readonly array $statements;

    /**
     * @param Parameter[] $parameters
     * @param AbstractStatement[] $statements
     */
    public function __construct(int $line, string $name, array $parameters, array $statements)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->parameters = $parameters;
        $this->statements = $statements;
    }
}
