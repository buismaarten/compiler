<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Semantic\SymbolResolver;

use Package\Exceptions\ExceptionInterface;
use RuntimeException;

final class UndefinedSymbolException extends RuntimeException implements ExceptionInterface
{
    public function __construct(int $line, string $name)
    {
        parent::__construct("Undefined symbol '{$name}' on line {$line}");
    }
}
