<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Common\Language;

enum Keyword: string
{
    case Break = 'break';
    case Const = 'const';
    case Continue = 'continue';
    case Else = 'else';
    case False = 'false';
    case Fn = 'fn';
    case If = 'if';
    case Null = 'null';
    case Print = 'print';
    case Return = 'return';
    case True = 'true';
    case Var = 'var';
    case While = 'while';
}
