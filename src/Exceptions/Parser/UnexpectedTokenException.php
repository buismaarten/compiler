<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Parser;

use Package\Exceptions\ExceptionInterface;
use RuntimeException;

final class UnexpectedTokenException extends RuntimeException implements ExceptionInterface
{
    public function __construct(int $line, string $token)
    {
        parent::__construct("Unexpected token '{$token}' on line {$line}");
    }
}
