<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Input;

use Package\Input\Input;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    #[Test]
    public function testCurrent(): void
    {
        $string = 'abc';
        $input = new Input($string);

        $this->assertEquals('a', $input->current());

        $input->next();
        $this->assertEquals('b', $input->current());

        $input->next();
        $this->assertEquals('c', $input->current());

        $input->next();
        $this->assertNull($input->current());
    }

    #[Test]
    public function testNext(): void
    {
        $string = 'abc';
        $input = new Input($string);

        $this->assertEquals('a', $input->current());

        $input->next();
        $this->assertEquals('b', $input->current());

        $input->next();
        $this->assertEquals('c', $input->current());

        $input->next();
        $this->assertNull($input->current());
    }

    #[Test]
    public function testOffset(): void
    {
        $string = 'abc';
        $input = new Input($string);

        $this->assertEquals(0, $input->offset());

        $input->next();
        $this->assertEquals(1, $input->offset());

        $input->next();
        $this->assertEquals(2, $input->offset());

        $input->next();
        $this->assertEquals(3, $input->offset());
    }

    #[Test]
    public function testSlice(): void
    {
        $string = 'abc';
        $input = new Input($string);

        $this->assertEquals('bc', $input->slice(1, 3));
    }
}
