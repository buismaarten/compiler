<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\Primitive;
use Package\Common\Language\UnaryOperator;
use Package\Semantic\TypeResolver\Resolvers\UnaryExpressionResolver;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnaryExpressionResolverTest extends TestCase
{
    #[Test]
    #[DataProvider('bitwiseNotProvider')]
    #[DataProvider('booleanNotProvider')]
    #[DataProvider('decrementProvider')]
    #[DataProvider('incrementProvider')]
    #[DataProvider('minusProvider')]
    #[DataProvider('plusProvider')]
    public function testResolve(UnaryOperator $operator, AbstractType $type, AbstractType $expected): void
    {
        $this->assertEquals($expected, UnaryExpressionResolver::resolve($operator, $type));
    }

    public static function bitwiseNotProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::BitwiseNot,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function booleanNotProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::BooleanNot,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'type' => new Types\PrimitiveType(Primitive::Bool),
            ],
        ];
    }

    public static function decrementProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::Decrement,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => UnaryOperator::Decrement,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
        ];
    }

    public static function incrementProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::Increment,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => UnaryOperator::Increment,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
        ];
    }

    public static function minusProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => UnaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
        ];
    }

    public static function plusProvider(): array
    {
        return [
            [
                'operator' => UnaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => UnaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
        ];
    }
}
