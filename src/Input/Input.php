<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Input;

use Override;

final class Input implements InputInterface
{
    private string $bytes;
    private int $offset = 0;

    public function __construct(string $bytes)
    {
        $this->bytes = $bytes;
    }

    #[Override]
    public function current(): ?string
    {
        return $this->bytes[$this->offset] ?? null;
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

        return substr($this->bytes, $start, $length);
    }
}
