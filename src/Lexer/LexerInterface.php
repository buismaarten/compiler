<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Lexer;

use Package\Token\Token;

interface LexerInterface
{
    /** @return Token[] */
    public function getTokens(): iterable;

    public function next(): Token;
}
