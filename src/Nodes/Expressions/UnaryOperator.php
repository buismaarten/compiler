<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum UnaryOperator
{
    case BITWISE_NOT;
    case BOOLEAN_NOT;
    case DECREMENT;
    case INCREMENT;
    case MINUS;
    case PLUS;

    public static function fromTokenType(TokenType $tokenType): self
    {
        // @phpstan-ignore-next-line
        return match ($tokenType) {
            TokenType::Decrement       => self::DECREMENT,
            TokenType::ExclamationMark => self::BOOLEAN_NOT,
            TokenType::Increment       => self::INCREMENT,
            TokenType::Minus           => self::MINUS,
            TokenType::Plus            => self::PLUS,
            TokenType::Tilde           => self::BITWISE_NOT,
        };
    }
}
