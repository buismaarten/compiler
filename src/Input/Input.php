<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Input;

use Override;

final class Input implements InputInterface
{
    private string $bytes;
    private int $position = 0;

    public function __construct(string $bytes)
    {
        $this->bytes = $bytes;
    }

    #[Override]
    public function current(): ?string
    {
        return $this->bytes[$this->position] ?? null;
    }

    #[Override]
    public function next(): void
    {
        $this->position++;
    }

    #[Override]
    public function position(): int
    {
        return $this->position;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        $length = $end - $start;

        return substr($this->bytes, $start, $length);
    }
}
