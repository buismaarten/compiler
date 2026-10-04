<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver\Resolvers;

use Package\Common\Language\Primitive;
use Package\Common\Language\BinaryOperator;
use Package\Semantic\TypeResolver\Resolvers\BinaryExpressionResolver;
use Package\Semantic\Types;
use Package\Semantic\Types\AbstractType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BinaryExpressionResolverTest extends TestCase
{
    #[Test]
    #[DataProvider('divideDataProvider')]
    #[DataProvider('minusDataProvider')]
    #[DataProvider('multiplyDataProvider')]
    #[DataProvider('plusDataProvider')]
    public function testResolve(BinaryOperator $operator, AbstractType $expected, AbstractType $left, AbstractType $right): void
    {
        $this->assertEquals($expected, BinaryExpressionResolver::resolve($operator, $left, $right));
    }

    // TODO
    public static function divideDataProvider(): array
    {
        return [
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Divide,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    // TODO
    public static function minusDataProvider(): array
    {
        return [
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    // TODO
    public static function multiplyDataProvider(): array
    {
        return [
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Multiply,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    // TODO
    public static function plusDataProvider(): array
    {
        return [
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Float)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::String),
                'left' => new Types\PrimitiveType(Primitive::String),
                'right' => new Types\PrimitiveType(Primitive::String),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::String),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::String)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::String)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::String),
                'left' => new Types\PrimitiveType(Primitive::String),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::String)),
            ],
            [
                'operator' => BinaryOperator::Plus,
                'expected' => new Types\PrimitiveType(Primitive::String),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::String)),
                'right' => new Types\PrimitiveType(Primitive::String),
            ],
        ];
    }
}
