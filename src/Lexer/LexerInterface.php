<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Lexer;

use Buismaarten\Parser\Token\Token;

interface LexerInterface
{
    public function next(): Token;

    /** @return Token[] */
    public function getTokens(): iterable;
}
