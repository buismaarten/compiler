<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

final class Scope
{
    public ?self $parent = null;

    /** @var array<int, string> */
    public array $symbols = [];

    public function __construct(?self $parent = null)
    {
        $this->parent = $parent;
    }
}
