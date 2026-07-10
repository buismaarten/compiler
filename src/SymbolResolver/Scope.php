<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\SymbolResolver;

use ArrayAccess;
use InvalidArgumentException;
use Override;
use Package\Nodes\AbstractNode;

/** @implements ArrayAccess<string, AbstractNode> */
final class Scope implements ArrayAccess
{
    public ?self $parent;

    /** @var array<string, AbstractNode> */
    private array $symbols = [];

    public function __construct(?self $parent)
    {
        $this->parent = $parent;
    }

    public function resolve(string $name): ?AbstractNode
    {
        for ($scope = $this; $scope !== null; $scope = $scope->parent) {
            $symbol = $scope->symbols[$name] ?? null;

            if ($symbol !== null) {
                return $symbol;
            }
        }

        return null;
    }

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->symbols[$offset]);
    }

    #[Override]
    public function offsetGet(mixed $offset): ?AbstractNode
    {
        return $this->symbols[$offset] ?? null;
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new InvalidArgumentException('Offset cannot be null');
        }

        $this->symbols[$offset] = $value;
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->symbols[$offset]);
    }
}
