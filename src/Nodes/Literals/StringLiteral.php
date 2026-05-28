<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractLiteral;

final class StringLiteral extends AbstractLiteral
{
    public readonly string $value;

    public function __construct(int $line, string $value)
    {
        parent::__construct($line);

        $this->value = $value;
    }
}
