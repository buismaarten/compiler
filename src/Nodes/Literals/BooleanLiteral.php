<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractLiteral;

final class BooleanLiteral extends AbstractLiteral
{
    public bool $value;

    public function __construct(bool $value, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->value = $value;
    }
}
