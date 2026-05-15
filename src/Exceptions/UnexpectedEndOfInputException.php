<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Exceptions;

final class UnexpectedEndOfInputException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?string $expected = null)
    {
        $message = 'Unexpected end of input';

        if ($expected !== null) {
            $message .= ", expected {$expected}";
        }

        parent::__construct($message);
    }
}
