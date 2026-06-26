<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Tokenizer\Token;

final class Location
{
    public readonly int $startLine;
    public readonly int $startOffset;
    public readonly int $endLine;
    public readonly int $endOffset;

    public function __construct(int $startLine, int $startOffset, int $endLine, int $endOffset)
    {
        $this->startLine = $startLine;
        $this->startOffset = $startOffset;
        $this->endLine = $endLine;
        $this->endOffset = $endOffset;
    }

    public static function fromToken(Token $startToken, Token $endToken): self
    {
        return new self(
            startLine: $startToken->line,
            startOffset: $startToken->startOffset,
            endLine: $endToken->line,
            endOffset: $endToken->endOffset,
        );
    }
}
