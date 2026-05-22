<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Token;

enum TokenType
{
    case T_EOF;
    case T_IDENTIFIER;
    case T_INTEGER;
    case T_KEYWORD_BREAK;
    case T_KEYWORD_CONTINUE;
    case T_KEYWORD_FALSE;
    case T_KEYWORD_NULL;
    case T_KEYWORD_TRUE;
    case T_KEYWORD_WHILE;
    case T_SEMICOLON;
}
