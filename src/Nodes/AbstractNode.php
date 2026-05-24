<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

abstract class AbstractNode implements \JsonSerializable
{
    public readonly int $line;

    public function __construct(int $line)
    {
        $this->line = $line;
    }

    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $type = explode('\\', static::class);
        $type = end($type);

        return ['_type' => $type] + get_object_vars($this);
    }
}
