<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use Override;

final class Input implements InputInterface
{
    private string $string;
    private int $offset = 0;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    #[Override]
    public function current(): ?string
    {
        return $this->string[$this->offset] ?? null;
    }

    #[Override]
    public function hash(): string
    {
        return hash('xxh128', $this->string);
    }

    #[Override]
    public function next(): void
    {
        $this->offset++;
    }

    #[Override]
    public function offset(): int
    {
        return $this->offset;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        $length = $end - $start;

        return substr($this->string, $start, $length);
    }
}
