<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractLiteral;

final class IntegerLiteral extends AbstractLiteral
{
    public int $value;

    public function __construct(int $value, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->value = $value;
    }
}
