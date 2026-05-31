<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Nodes\Enums\Type;

final class Parameter extends AbstractNode
{
    public readonly string $name;
    public readonly Type $type;

    public function __construct(int $line, string $name, Type $type)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->type = $type;
    }
}
