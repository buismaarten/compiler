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
            TokenType::T_DECREMENT        => self::DECREMENT,
            TokenType::T_EXCLAMATION_MARK => self::BOOLEAN_NOT,
            TokenType::T_INCREMENT        => self::INCREMENT,
            TokenType::T_MINUS            => self::MINUS,
            TokenType::T_PLUS             => self::PLUS,
            TokenType::T_TILDE            => self::BITWISE_NOT,
        };
    }
}
