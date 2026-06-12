<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use Override;

final class StringInput implements InputInterface
{
    use InputTrait;

    private string $string;

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
    public function slice(int $start, int $end): string
    {
        return substr($this->string, $start, $end - $start);
    }
}
