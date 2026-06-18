<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    case BitwiseAnd;
    case BitwiseOr;
    case BitwiseXor;
    case Divide;
    case Equal;
    case Exponent;
    case GreaterThan;
    case GreaterThanEqual;
    case Identical;
    case LessThan;
    case LessThanEqual;
    case Minus;
    case Modulo;
    case Multiply;
    case NotEqual;
    case NotIdentical;
    case Plus;
    case ShiftLeft;
    case ShiftRight;
    case Spaceship;

    public static function fromTokenType(TokenType $tokenType): ?self
    {
        return match ($tokenType) {
            TokenType::BitwiseAnd       => self::BitwiseAnd,
            TokenType::BitwiseOr        => self::BitwiseOr,
            TokenType::BitwiseXor       => self::BitwiseXor,
            TokenType::Divide           => self::Divide,
            TokenType::Equal            => self::Equal,
            TokenType::Exponent         => self::Exponent,
            TokenType::GreaterThan      => self::GreaterThan,
            TokenType::GreaterThanEqual => self::GreaterThanEqual,
            TokenType::Identical        => self::Identical,
            TokenType::LessThan         => self::LessThan,
            TokenType::LessThanEqual    => self::LessThanEqual,
            TokenType::Minus            => self::Minus,
            TokenType::Modulo           => self::Modulo,
            TokenType::Multiply         => self::Multiply,
            TokenType::NotEqual         => self::NotEqual,
            TokenType::NotIdentical     => self::NotIdentical,
            TokenType::Plus             => self::Plus,
            TokenType::ShiftLeft        => self::ShiftLeft,
            TokenType::ShiftRight       => self::ShiftRight,
            TokenType::Spaceship        => self::Spaceship,

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
            self::Exponent         => 9,
            self::Divide           => 8,
            self::Modulo           => 8,
            self::Multiply         => 8,
            self::Minus            => 7,
            self::Plus             => 7,
            self::ShiftLeft        => 6,
            self::ShiftRight       => 6,
            self::GreaterThan      => 5,
            self::GreaterThanEqual => 5,
            self::LessThan         => 5,
            self::LessThanEqual    => 5,
            self::Equal            => 4,
            self::Identical        => 4,
            self::NotEqual         => 4,
            self::NotIdentical     => 4,
            self::Spaceship        => 4,
            self::BitwiseAnd       => 3,
            self::BitwiseXor       => 2,
            self::BitwiseOr        => 1,
        };
    }
}
