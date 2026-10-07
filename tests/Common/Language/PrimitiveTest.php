<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Common\Language;

use Package\Common\Language\Primitive;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class PrimitiveTest extends TestCase
{
    #[Test]
    #[TestWith([Primitive::Bool, false])]
    #[TestWith([Primitive::Float, true])]
    #[TestWith([Primitive::Integer, true])]
    #[TestWith([Primitive::Null, false])]
    #[TestWith([Primitive::String, false])]
    #[TestWith([Primitive::Void, false])]
    public function testIsNumeric(Primitive $type, bool $expected): void
    {
        $this->assertSame($expected, $type->isNumeric());
    }

    #[Test]
    #[TestWith([Primitive::Float, Primitive::Integer, Primitive::Float])]
    #[TestWith([Primitive::Integer, Primitive::Float, Primitive::Float])]
    #[TestWith([Primitive::Null, Primitive::Void, Primitive::Null])]
    #[TestWith([Primitive::Void, Primitive::Null, Primitive::Null])]
    public function testPromote(Primitive $type, Primitive $other, Primitive $expected): void
    {
        $this->assertSame($expected, $type->promote($other));
    }
}
