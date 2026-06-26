<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use JsonSerializable;

abstract class AbstractNode implements JsonSerializable
{
    private Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getStartLine(): int
    {
        return $this->getLocation()->startLine;
    }

    public function getEndLine(): int
    {
        return $this->getLocation()->endLine;
    }

    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $type = explode('\\', static::class);
        $type = end($type);

        return ['_type' => $type] + get_object_vars($this);
    }
}
