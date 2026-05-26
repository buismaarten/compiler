<?php

declare(strict_types=1);

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
        $input = new Input('abc');
        $this->assertSame('a', $input->current());

        $input->next();
        $this->assertSame('b', $input->current());

        $input->next();
        $this->assertSame('c', $input->current());

        $input->next();
        $this->assertNull($input->current());
    }

    #[Test]
    public function testNext(): void
    {
        $input = new Input('abc');
        $this->assertSame('a', $input->current());

        $input->next();
        $this->assertSame('b', $input->current());

        $input->next();
        $this->assertSame('c', $input->current());

        $input->next();
        $this->assertNull($input->current());
    }

    #[Test]
    public function testOffset(): void
    {
        $input = new Input('abc');
        $this->assertSame(0, $input->offset());

        $input->next();
        $this->assertSame(1, $input->offset());

        $input->next();
        $this->assertSame(2, $input->offset());

        $input->next();
        $this->assertSame(3, $input->offset());
    }

    #[Test]
    public function testSlice(): void
    {
        $input = new Input('abc');

        $this->assertSame('bc', $input->slice(1, 3));
    }
}
