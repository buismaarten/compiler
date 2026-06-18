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
    case BooleanAnd;
    case BooleanOr;
    case Divide;
    case Equal;
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
            TokenType::BooleanAnd       => self::BooleanAnd,
            TokenType::BooleanOr        => self::BooleanOr,
            TokenType::Divide           => self::Divide,
            TokenType::Equal            => self::Equal,
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

    public function precedence(): int
    {
        return match ($this) {
            self::Divide           => 10,
            self::Modulo           => 10,
            self::Multiply         => 10,
            self::Minus            => 9,
            self::Plus             => 9,
            self::ShiftLeft        => 8,
            self::ShiftRight       => 8,
            self::GreaterThan      => 7,
            self::GreaterThanEqual => 7,
            self::LessThan         => 7,
            self::LessThanEqual    => 7,
            self::Equal            => 6,
            self::Identical        => 6,
            self::NotEqual         => 6,
            self::NotIdentical     => 6,
            self::Spaceship        => 6,
            self::BitwiseAnd       => 5,
            self::BitwiseXor       => 4,
            self::BitwiseOr        => 3,
            self::BooleanAnd       => 2,
            self::BooleanOr        => 1,
        };
    }
}
