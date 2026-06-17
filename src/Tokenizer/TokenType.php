<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

enum TokenType
{
    case Assign;
    case Asterisk;
    case Colon;
    case Comma;
    case Decrement;
    case Eof;
    case Equal;
    case ExclamationMark;
    case Exponent;
    case Float;
    case GreaterThan;
    case GreaterThanEqual;
    case Identical;
    case Identifier;
    case Increment;
    case Integer;
    case KeywordBreak;
    case KeywordConst;
    case KeywordContinue;
    case KeywordElse;
    case KeywordFalse;
    case KeywordFn;
    case KeywordIf;
    case KeywordNull;
    case KeywordPrint;
    case KeywordReturn;
    case KeywordTrue;
    case KeywordVar;
    case KeywordWhile;
    case LeftBrace;
    case LeftBracket;
    case LeftParenthesis;
    case LessThan;
    case LessThanEqual;
    case Minus;
    case NotEqual;
    case NotIdentical;
    case Percent;
    case Plus;
    case QuestionMark;
    case RightBrace;
    case RightBracket;
    case RightParenthesis;
    case Semicolon;
    case ShiftLeft;
    case ShiftRight;
    case Slash;
    case Spaceship;
    case String;
    case Tilde;

    // TODO
    case Ampersand;
    case Caret;
    case Pipe;
}
