<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Statements;

use Package\Nodes\AbstractStatement;
use Package\Nodes\Location;

final class ImportStatement extends AbstractStatement
{
    public string $path;

    public function __construct(Location $location, string $path)
    {
        parent::__construct($location);

        $this->path = $path;
    }
}
