<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Token;

enum TokenType implements \JsonSerializable
{
    case T_EOF;
    case T_MULTIPLY;
    case T_NUMBER;
    case T_PLUS;

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
