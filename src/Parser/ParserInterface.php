<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Parser;

use Package\Lexer\LexerInterface;
use Package\Nodes\AbstractNode;

interface ParserInterface
{
    public function getLexer(): LexerInterface;

    public function parse(): AbstractNode;
}
