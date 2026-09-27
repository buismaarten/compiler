<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Common\Location;
use Package\Nodes\Block;

final class BlockStatement extends AbstractStatement
{
    public Block $block;

    public function __construct(Location $location, Block $block)
    {
        parent::__construct($location);

        $this->block = $block;
    }
}
