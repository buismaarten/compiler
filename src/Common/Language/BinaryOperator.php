<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Common\Language;

enum BinaryOperator: string
{
    private const PRECEDENCE = [
        [self::Divide, self::Modulo, self::Multiply],
        [self::Minus, self::Plus],
        [self::ShiftLeft, self::ShiftRight],
        [self::GreaterThan, self::GreaterThanEqual, self::LessThan, self::LessThanEqual],
        [self::Equal, self::NotEqual, self::Spaceship],
        [self::BitwiseAnd],
        [self::BitwiseXor],
        [self::BitwiseOr],
        [self::BooleanAnd],
        [self::BooleanOr],
    ];

    case BitwiseAnd = '&';
    case BitwiseOr = '|';
    case BitwiseXor = '^';
    case BooleanAnd = '&&';
    case BooleanOr = '||';
    case Divide = '/';
    case Equal = '==';
    case GreaterThan = '>';
    case GreaterThanEqual = '>=';
    case LessThan = '<';
    case LessThanEqual = '<=';
    case Minus = '-';
    case Modulo = '%';
    case Multiply = '*';
    case NotEqual = '!=';
    case Plus = '+';
    case ShiftLeft = '<<';
    case ShiftRight = '>>';
    case Spaceship = '<=>';

    public function precedence(): int
    {
        static $mapping = [];

        if ($mapping === []) {
            $count = count(self::PRECEDENCE);

            foreach (self::PRECEDENCE as $index => $operators) {
                $precedence = $count - $index;

                foreach ($operators as $operator) {
                    $mapping[$operator->name] = $precedence;
                }
            }
        }

        // @phpstan-ignore-next-line
        return $mapping[$this->name] ?? 0;
    }
}
