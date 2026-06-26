<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractStatement;
use Package\Nodes\AbstractType;
use Package\Nodes\Location;
use Package\Nodes\Parameter;

final class FunctionStatement extends AbstractStatement
{
    public string $name;
    public AbstractType $type;

    /** @var Parameter[] */
    public array $parameters;

    /** @var AbstractStatement[] */
    public array $statements;

    /**
     * @param Parameter[] $parameters
     * @param AbstractStatement[] $statements
     */
    public function __construct(Location $location, string $name, AbstractType $type, array $parameters, array $statements)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->type = $type;
        $this->parameters = $parameters;
        $this->statements = $statements;
    }
}
