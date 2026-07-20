<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Symbols;

enum ValueSymbolKind
{
    case Constant;
    case Parameter;
    case Variable;
}
