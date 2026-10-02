<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Common;

final readonly class Location
{
    public int $startLine;
    public int $startOffset;
    public int $endLine;
    public int $endOffset;

    public function __construct(int $startLine, int $startOffset, int $endLine, int $endOffset)
    {
        $this->startLine = $startLine;
        $this->startOffset = $startOffset;
        $this->endLine = $endLine;
        $this->endOffset = $endOffset;
    }
}
