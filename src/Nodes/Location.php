<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes;

use Package\Tokenizer\Token;

final class Location
{
    public readonly int $startLine;
    public readonly int $endLine;

    public function __construct(int $startLine, int $endLine)
    {
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }

    public static function fromToken(Token $startToken, Token $endToken): self
    {
        return new self(
            startLine: $startToken->line,
            endLine: $endToken->line,
        );
    }
}
