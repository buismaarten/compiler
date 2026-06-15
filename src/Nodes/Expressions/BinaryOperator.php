<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    case Plus;
    case Minus;
    case Multiply;
    case Exponentiation;

    public static function fromTokenType(TokenType $tokenType): ?self
    {
        return match ($tokenType) {
            TokenType::Asterisk => self::Multiply,
            TokenType::Exponent => self::Exponentiation,
            TokenType::Minus    => self::Minus,
            TokenType::Plus     => self::Plus,

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
            self::Multiply       => 2,
            self::Plus           => 1,
            self::Minus          => 1,
        };
    }
}
