<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

enum TokenType
{
    case Assign;
    case BitwiseAnd;
    case BitwiseNot;
    case BitwiseOr;
    case BitwiseXor;
    case BooleanAnd;
    case BooleanNot;
    case BooleanOr;
    case Colon;
    case Comma;
    case Decrement;
    case Divide;
    case Eof;
    case Equal;
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
    case KeywordImport;
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
    case Modulo;
    case Multiply;
    case NotEqual;
    case NotIdentical;
    case Plus;
    case QuestionMark;
    case RightBrace;
    case RightBracket;
    case RightParenthesis;
    case Semicolon;
    case ShiftLeft;
    case ShiftRight;
    case Spaceship;
    case String;
}
