<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

enum TokenType
{
    case ASSIGN;
    case ASTERISK;
    case COLON;
    case COMMA;
    case DECREMENT;
    case EOF;
    case EXCLAMATION_MARK;
    case FLOAT;
    case IDENTIFIER;
    case INCREMENT;
    case INTEGER;
    case KEYWORD_BREAK;
    case KEYWORD_CONST;
    case KEYWORD_CONTINUE;
    case KEYWORD_ELSE;
    case KEYWORD_FALSE;
    case KEYWORD_FN;
    case KEYWORD_IF;
    case KEYWORD_NULL;
    case KEYWORD_PRINT;
    case KEYWORD_RETURN;
    case KEYWORD_TRUE;
    case KEYWORD_VAR;
    case KEYWORD_WHILE;
    case LEFT_BRACE;
    case LEFT_BRACKET;
    case LEFT_PARENTHESIS;
    case MINUS;
    case PLUS;
    case QUESTION_MARK;
    case RIGHT_BRACE;
    case RIGHT_BRACKET;
    case RIGHT_PARENTHESIS;
    case SEMICOLON;
    case SLASH;
    case STRING;
    case TILDE;
}
