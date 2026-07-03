<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractStatement;

final class Scope
{
    public ?Scope $parent = null;

    /** @var array<string, AbstractStatement> */
    public array $symbols = [];

    public function __construct(?Scope $parent = null)
    {
        $this->parent = $parent;
    }

    public function define(string $symbol, AbstractStatement $declaration): void
    {
        $this->symbols[$symbol] = $declaration;
    }

    public function hasInCurrentScope(string $symbol): bool
    {
        return isset($this->symbols[$symbol]);
    }

    public function find(string $symbol): AbstractStatement|null
    {
        if ($this->hasInCurrentScope($symbol)) {
            return $this->symbols[$symbol];
        }

        return $this->parent?->find($symbol);
    }
}
