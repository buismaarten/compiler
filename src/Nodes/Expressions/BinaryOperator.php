<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    case Divide;
    case Exponent;
    case GreaterThan;
    case GreaterThanEqual;
    case LessThan;
    case LessThanEqual;
    case Minus;
    case Modulo;
    case Multiply;
    case Plus;
    case ShiftLeft;
    case ShiftRight;

    public static function fromTokenType(TokenType $tokenType): ?self
    {
        return match ($tokenType) {
            TokenType::Asterisk         => self::Multiply,
            TokenType::Exponent         => self::Exponent,
            TokenType::GreaterThan      => self::GreaterThan,
            TokenType::GreaterThanEqual => self::GreaterThanEqual,
            TokenType::LessThan         => self::LessThan,
            TokenType::LessThanEqual    => self::LessThanEqual,
            TokenType::Minus            => self::Minus,
            TokenType::Percent          => self::Modulo,
            TokenType::Plus             => self::Plus,
            TokenType::ShiftLeft        => self::ShiftLeft,
            TokenType::ShiftRight       => self::ShiftRight,
            TokenType::Slash            => self::Divide,

            // Fallback
            default => null,
        };
    }

    public function isRightAssociative(): bool
    {
        return match ($this) {
            self::Exponent => true,

            // Fallback
            default => false,
        };
    }

    public function precedence(): int
    {
        return match ($this) {
            self::Exponent         => 5,
            self::Divide           => 4,
            self::Modulo           => 4,
            self::Multiply         => 4,
            self::Minus            => 3,
            self::Plus             => 3,
            self::ShiftLeft        => 2,
            self::ShiftRight       => 2,
            self::GreaterThan      => 1,
            self::GreaterThanEqual => 1,
            self::LessThan         => 1,
            self::LessThanEqual    => 1,
        };
    }
}
