<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Internal;

use UnderflowException;

/** @template T */
final class Stack
{
    /** @var array<T> */
    private array $elements = [];

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /** @return T */
    public function peek(): mixed
    {
        if (empty($this->elements)) {
            throw new UnderflowException('Stack is empty');
        }

        return end($this->elements);
    }

    /** @return T */
    public function pop(): mixed
    {
        if (empty($this->elements)) {
            throw new UnderflowException('Stack is empty');
        }

        return array_pop($this->elements);
    }

    /** @param T $element */
    public function push(mixed $element): void
    {
        $this->elements[] = $element;
    }
}
