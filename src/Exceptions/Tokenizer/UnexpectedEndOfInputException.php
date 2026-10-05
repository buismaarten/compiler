<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Tokenizer;

use Package\Exceptions\ExceptionInterface;
use RuntimeException;

final class UnexpectedEndOfInputException extends RuntimeException implements ExceptionInterface
{
    public function __construct(int $line)
    {
        parent::__construct("Unexpected end of input on line {$line}");
    }
}
