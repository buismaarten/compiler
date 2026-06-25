<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class IdentifierExpression extends AbstractExpression
{
    public string $name;

    public function __construct(string $name, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $name;
    }
}
