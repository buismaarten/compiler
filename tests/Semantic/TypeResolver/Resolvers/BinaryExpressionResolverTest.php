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
    #[DataProvider('dataProvider')]
    public function testResolve(BinaryOperator $operator, AbstractType $expected, AbstractType $left, AbstractType $right): void
    {
        $this->assertEquals($expected, BinaryExpressionResolver::resolve($operator, $left, $right));
    }

    // TODO
    public static function dataProvider(): array
    {
        return [
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Integer),
                'left' => new Types\PrimitiveType(Primitive::Integer),
                'right' => new Types\PrimitiveType(Primitive::Integer),
            ],
            [
                'operator' => BinaryOperator::Minus,
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
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
                'expected' => new Types\PrimitiveType(Primitive::Float),
                'left' => new Types\PrimitiveType(Primitive::Float),
                'right' => new Types\PrimitiveType(Primitive::Float),
            ],
        ];
    }
}
