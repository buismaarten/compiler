<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use JsonSerializable;
use Package\Core\Location;

abstract class AbstractNode implements JsonSerializable
{
    private Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function getStartLine(): int
    {
        return $this->location->startLine;
    }

    public function getStartOffset(): int
    {
        return $this->location->startOffset;
    }

    public function getEndLine(): int
    {
        return $this->location->endLine;
    }

    public function getEndOffset(): int
    {
        return $this->location->endOffset;
    }

    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $type = explode('\\', static::class);
        $type = end($type);

        $array = (array) get_object_vars(...)->__invoke($this);
        $array = ['_type' => $type, '_location' => $this->location] + $array;

        return $array;
    }
}
