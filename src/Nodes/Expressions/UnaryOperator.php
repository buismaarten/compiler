<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Tokenizer\TokenType;

enum UnaryOperator
{
    case BOOLEAN_NOT;

    public static function fromTokenType(TokenType $tokenType): self
    {
        // @phpstan-ignore-next-line
        return match ($tokenType) {
            TokenType::T_EXCLAMATION_MARK => self::BOOLEAN_NOT,
        };
    }
}
