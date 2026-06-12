<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use Override;

trait InputTrait
{
    private int $offset = 0;

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
}
