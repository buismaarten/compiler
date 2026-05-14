<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

final class Token
{
    public TokenType $type;
    public string $value;

    public function __construct(TokenType $type, string $value = '')
    {
        $this->type = $type;
        $this->value = $value;
    }
}
