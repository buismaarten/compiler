<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Lexer;

use Package\Exceptions\ExceptionInterface;

final class UnexpectedCharacterException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(int $line, string $character)
    {
        parent::__construct("Unexpected character '{$character}' on line {$line}");
    }
}
