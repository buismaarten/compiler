<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum UnaryOperator
{
    case BitwiseNot;
    case BooleanNot;
    case Decrement;
    case Increment;
    case Minus;
    case Plus;

    public static function fromTokenType(TokenType $tokenType): self
    {
        // @phpstan-ignore-next-line
        return match ($tokenType) {
            TokenType::Decrement       => self::Decrement,
            TokenType::ExclamationMark => self::BooleanNot,
            TokenType::Increment       => self::Increment,
            TokenType::Minus           => self::Minus,
            TokenType::Plus            => self::Plus,
            TokenType::Tilde           => self::BitwiseNot,
        };
    }
}
