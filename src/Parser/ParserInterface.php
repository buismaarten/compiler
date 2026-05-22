<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Parser;

use Package\Nodes\AbstractStatement;

interface ParserInterface
{
    /** @return AbstractStatement[] */
    public function parse(): array;
}
