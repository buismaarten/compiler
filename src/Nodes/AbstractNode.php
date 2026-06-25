<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use JsonSerializable;

abstract class AbstractNode implements JsonSerializable
{
    /** @var array<mixed> */
    private array $attributes = [];

    /** @param array<mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    // TODO
    public function getLineStart(): int
    {
        // @phpstan-ignore-next-line
        return intval($this->attributes['lineStart'] ?? 0);
    }

    // TODO
    public function getLineEnd(): int
    {
        // @phpstan-ignore-next-line
        return intval($this->attributes['lineEnd'] ?? 0);
    }

    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $type = explode('\\', static::class);
        $type = end($type);

        return ['_type' => $type] + get_object_vars($this);
    }
}
