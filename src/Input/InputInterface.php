<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

interface InputInterface
{
    public function current(): ?string;

    public function next(): void;

    public function offset(): int;

    public function slice(int $start, int $end): string;
}
