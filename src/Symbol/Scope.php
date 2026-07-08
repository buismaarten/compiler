<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractNode;

final class Scope
{
    public ?self $parent;

    /** @var array<string, AbstractNode> */
    public array $symbols = [];

    public function __construct(?self $parent = null)
    {
        $this->parent = $parent;
    }

    public function exists(string $name): bool
    {
        return isset($this->symbols[$name]);
    }

    public function set(string $name, AbstractNode $node): void
    {
        $this->symbols[$name] = $node;
    }
}
