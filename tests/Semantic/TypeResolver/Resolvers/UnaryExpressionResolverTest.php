<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\Primitive;
use Package\Common\Language\UnaryOperator;
use Package\Semantic\TypeResolver\Resolvers\UnaryExpressionResolver;
use Package\Types;
use Package\Types\AbstractType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnaryExpressionResolverTest extends TestCase
{
    #[Test]
    #[DataProvider('dataProviderBitwiseNot')]
    #[DataProvider('dataProviderBooleanNot')]
    #[DataProvider('dataProviderDecrement')]
    #[DataProvider('dataProviderIncrement')]
    #[DataProvider('dataProviderMinus')]
    #[DataProvider('dataProviderPlus')]
    public function testResolve(UnaryOperator $operator, AbstractType $expected, AbstractType $type): void
    {
        $this->assertEquals($expected, UnaryExpressionResolver::resolve($operator, $type));
    }

    public static function dataProviderBitwiseNot(): array
    {
        return [
            [
                'operator' => UnaryOperator::BitwiseNot,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderBooleanNot(): array
    {
        return [
            [
                'operator' => UnaryOperator::BooleanNot,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'type' => new Types\PrimitiveType(Primitive::Bool),
            ],
        ];
    }

    public static function dataProviderDecrement(): array
    {
        return [
            [
                'operator' => UnaryOperator::Decrement,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => UnaryOperator::Decrement,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderIncrement(): array
    {
        return [
            [
                'operator' => UnaryOperator::Increment,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => UnaryOperator::Increment,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderMinus(): array
    {
        return [
            [
                'operator' => UnaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => UnaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderPlus(): array
    {
        return [
            [
                'operator' => UnaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'type' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => UnaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'type' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }
}
