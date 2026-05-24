<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractLiteral;

final class BooleanLiteral extends AbstractLiteral
{
    public readonly bool $value;

    public function __construct(int $line, bool $value)
    {
        parent::__construct($line);

        $this->value = $value;
    }
}
