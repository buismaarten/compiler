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

// TODO
class BinaryExpressionResolverTest extends TestCase
{
    #[Test]
    #[DataProvider('dataProviderBitwiseAnd')]
    #[DataProvider('dataProviderBitwiseOr')]
    #[DataProvider('dataProviderBitwiseXor')]
    #[DataProvider('dataProviderBooleanAnd')]
    #[DataProvider('dataProviderBooleanOr')]
    #[DataProvider('dataProviderDivide')]
    #[DataProvider('dataProviderMinus')]
    #[DataProvider('dataProviderMultiply')]
    #[DataProvider('dataProviderPlus')]
    #[DataProvider('dataProviderShiftLeft')]
    #[DataProvider('dataProviderShiftRight')]
    public function testResolve(BinaryOperator $operator, AbstractType $expected, AbstractType $left, AbstractType $right): void
    {
        $this->assertEquals($expected, BinaryExpressionResolver::resolve($operator, $left, $right));
    }

    public static function dataProviderBitwiseAnd(): array
    {
        return [
            [
                'operator' => BinaryOperator::BitwiseAnd,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BitwiseAnd,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::BitwiseAnd,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
            [
                'operator' => BinaryOperator::BitwiseAnd,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderBitwiseOr(): array
    {
        return [
            [
                'operator' => BinaryOperator::BitwiseOr,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BitwiseOr,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::BitwiseOr,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
            [
                'operator' => BinaryOperator::BitwiseOr,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderBitwiseXor(): array
    {
        return [
            [
                'operator' => BinaryOperator::BitwiseXor,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BitwiseXor,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Integer)),
            ],
            [
                'operator' => BinaryOperator::BitwiseXor,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
            [
                'operator' => BinaryOperator::BitwiseXor,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderBooleanAnd(): array
    {
        return [
            [
                'operator' => BinaryOperator::BooleanAnd,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BooleanAnd,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
            [
                'operator' => BinaryOperator::BooleanAnd,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BooleanAnd,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
        ];
    }

    public static function dataProviderBooleanOr(): array
    {
        return [
            [
                'operator' => BinaryOperator::BooleanOr,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BooleanOr,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
            [
                'operator' => BinaryOperator::BooleanOr,
                'expected' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\NullableType(new Types\PrimitiveType(Primitive::Bool)),
            ],
            [
                'operator' => BinaryOperator::BooleanOr,
                'expected' => new Types\PrimitiveType(Primitive::Bool),
                'left' => new Types\PrimitiveType(Primitive::Bool),
                'right' => new Types\PrimitiveType(Primitive::Bool),
            ],
        ];
    }

    public static function dataProviderDivide(): array
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

    public static function dataProviderMinus(): array
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

    public static function dataProviderMultiply(): array
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

    public static function dataProviderPlus(): array
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

    public static function dataProviderShiftLeft(): array
    {
        return [
            [
                'operator' => BinaryOperator::ShiftLeft,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }

    public static function dataProviderShiftRight(): array
    {
        return [
            [
                'operator' => BinaryOperator::ShiftRight,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
        ];
    }
}
