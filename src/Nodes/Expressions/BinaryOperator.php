<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    case Addition;
    case Exponentiation;
    case Multiplication;
    case Subtraction;

    public static function fromTokenType(TokenType $tokenType): ?self
    {
        return match ($tokenType) {
            TokenType::Asterisk => self::Multiplication,
            TokenType::Exponent => self::Exponentiation,
            TokenType::Minus    => self::Subtraction,
            TokenType::Plus     => self::Addition,

            // Fallback
            default => null,
        };
    }

    public function isRightAssociative(): bool
    {
        return match ($this) {
            self::Exponentiation => true,

            // Fallback
            default => false,
        };
    }

    public function precedence(): int
    {
        return match ($this) {
            self::Exponentiation => 3,
            self::Multiplication => 2,
            self::Addition       => 1,
            self::Subtraction    => 1,
        };
    }
}
