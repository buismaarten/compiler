<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Token;

enum TokenType
{
    case T_ASSIGN;
    case T_ASTERISK;
    case T_COMMA;
    case T_EOF;
    case T_FLOAT;
    case T_IDENTIFIER;
    case T_INTEGER;
    case T_KEYWORD_BREAK;
    case T_KEYWORD_CONST;
    case T_KEYWORD_CONTINUE;
    case T_KEYWORD_ELSE;
    case T_KEYWORD_FALSE;
    case T_KEYWORD_IF;
    case T_KEYWORD_NULL;
    case T_KEYWORD_PRINT;
    case T_KEYWORD_RETURN;
    case T_KEYWORD_TRUE;
    case T_KEYWORD_VAR;
    case T_KEYWORD_WHILE;
    case T_LEFT_BRACE;
    case T_LEFT_BRACKET;
    case T_LEFT_PARENTHESIS;
    case T_MINUS;
    case T_PLUS;
    case T_RIGHT_BRACE;
    case T_RIGHT_BRACKET;
    case T_RIGHT_PARENTHESIS;
    case T_SEMICOLON;
    case T_SLASH;
}
