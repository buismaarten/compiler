<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Token;

final class Token
{
    public TokenType $type;
    public int $line;
    public string $value;

    public function __construct(TokenType $type, int $line, string $value = '')
    {
        $this->type = $type;
        $this->line = $line;
        $this->value = $value;
    }
}
