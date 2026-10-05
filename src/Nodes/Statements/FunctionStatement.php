<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Common\Location;
use Package\Nodes\Block;
use Package\Nodes\Parameter;
use Package\Nodes\Types\AbstractType;

final class FunctionStatement extends AbstractStatement
{
    public string $name;
    public AbstractType $type;

    /** @var Parameter[] */
    public array $parameters;

    public Block $statements;

    /** @param Parameter[] $parameters */
    public function __construct(Location $location, string $name, AbstractType $type, array $parameters, Block $statements)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->type = $type;
        $this->parameters = $parameters;
        $this->statements = $statements;
    }
}
