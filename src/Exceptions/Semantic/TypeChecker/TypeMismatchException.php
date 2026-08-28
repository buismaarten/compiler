<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Exceptions\Semantic\TypeChecker;

use Package\Exceptions\ExceptionInterface;
use RuntimeException;

// TODO
final class TypeMismatchException extends RuntimeException implements ExceptionInterface
{
    public function __construct(int $line)
    {
        parent::__construct("Type mismatch on line {$line}");
    }
}
