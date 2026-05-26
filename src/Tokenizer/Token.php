<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

final class Token
{
    public readonly TokenType $type;
    public readonly int $line;
    public readonly string $value;

    public function __construct(TokenType $type, int $line, string $value = '')
    {
        $this->type = $type;
        $this->line = $line;
        $this->value = $value;
    }
}
