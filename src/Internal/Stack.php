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

    /** @param T $element */
    public function push(mixed $element): void
    {
        $this->elements[] = $element;
    }

    /** @return T */
    public function pop(): mixed
    {
        $element = array_pop($this->elements);

        if ($element === null) {
            throw new UnderflowException('Stack is empty');
        }

        return $element;
    }

    /** @return T */
    public function peek(): mixed
    {
        $element = end($this->elements);

        if ($element === false) {
            throw new UnderflowException('Stack is empty');
        }

        return $element;
    }
}
