<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Nodes\Expressions\IdentifierExpression;

final class Parameter extends AbstractNode
{
    public readonly string $name;
    public readonly IdentifierExpression $type;

    public function __construct(int $line, string $name, IdentifierExpression $type)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->type = $type;
    }
}
