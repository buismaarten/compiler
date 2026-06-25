<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

final class Parameter extends AbstractNode
{
    public string $name;
    public AbstractType $type;

    public function __construct(string $name, AbstractType $type, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $name;
        $this->type = $type;
    }
}
