<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Parser;

use Package\Exceptions\ExceptionInterface;
use RuntimeException;

final class ExpectedTokenException extends RuntimeException implements ExceptionInterface
{
    public function __construct(int $line, string $expected, string $found)
    {
        parent::__construct("Expected token '{$expected}', found '{$found}' on line {$line}");
    }
}
