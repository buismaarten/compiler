<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    case Addition;
    case Subtraction;

    public static function fromTokenType(TokenType $tokenType): self
    {
        // @phpstan-ignore-next-line
        return match ($tokenType) {
            TokenType::Minus => self::Subtraction,
            TokenType::Plus  => self::Addition,
        };
    }
}
