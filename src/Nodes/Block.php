<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Common\Location;
use Package\Nodes\Statements\AbstractStatement;

final class Block extends AbstractNode
{
    /** @var array<AbstractStatement> */
    public array $statements;

    /** @param array<AbstractStatement> $statements */
    public function __construct(Location $location, array $statements)
    {
        parent::__construct($location);

        $this->statements = $statements;
    }
}
