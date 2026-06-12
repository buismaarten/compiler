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
            TokenType::DECREMENT        => self::DECREMENT,
            TokenType::EXCLAMATION_MARK => self::BOOLEAN_NOT,
            TokenType::INCREMENT        => self::INCREMENT,
            TokenType::MINUS            => self::MINUS,
            TokenType::PLUS             => self::PLUS,
            TokenType::TILDE            => self::BITWISE_NOT,
        };
    }
}
