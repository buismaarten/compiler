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

    // TODO
    case Equal;
    case Identical;
    case NotEqual;
    case NotIdentical;
    case Spaceship;

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
            self::Exponent         => 6,
            self::Divide           => 5,
            self::Modulo           => 5,
            self::Multiply         => 5,
            self::Minus            => 4,
            self::Plus             => 4,
            self::ShiftLeft        => 3,
            self::ShiftRight       => 3,
            self::GreaterThan      => 2,
            self::GreaterThanEqual => 2,
            self::LessThan         => 2,
            self::LessThanEqual    => 2,

            // TODO
            self::Equal            => 1,
            self::Identical        => 1,
            self::NotEqual         => 1,
            self::NotIdentical     => 1,
            self::Spaceship        => 1,
        };
    }
}
