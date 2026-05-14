<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Token;

enum TokenType
{
    case NUMBER;
    case PLUS;
    case MUL;
}
