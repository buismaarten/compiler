<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum BinaryOperator
{
    private const PRECEDENCE = [
        [self::Divide, self::Modulo, self::Multiply],
        [self::Minus, self::Plus],
        [self::ShiftLeft, self::ShiftRight],
        [self::GreaterThan, self::GreaterThanEqual, self::LessThan, self::LessThanEqual],
        [self::Equal, self::Identical, self::NotEqual, self::NotIdentical, self::Spaceship],
        [self::BitwiseAnd],
        [self::BitwiseXor],
        [self::BitwiseOr],
        [self::BooleanAnd],
        [self::BooleanOr],
    ];

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
        foreach (self::PRECEDENCE as $index => $operators) {
            if (in_array($this, $operators, true)) {
                return count(self::PRECEDENCE) - $index;
            }
        }

        // Fallback
        return 0;
    }
}
