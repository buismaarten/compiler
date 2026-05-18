<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Token;

enum TokenType
{
    case T_EOF;
    case T_IDENTIFIER;
    case T_MULTIPLY;
    case T_NUMBER;
    case T_PLUS;
}
