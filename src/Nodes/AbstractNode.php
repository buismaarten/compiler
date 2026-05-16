<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Nodes;

abstract class AbstractNode implements \JsonSerializable
{
    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        return ['_type' => static::class] + get_object_vars($this);
    }
}
