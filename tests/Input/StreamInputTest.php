<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Input;

use Package\Input\StreamInput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StreamInputTest extends TestCase
{
    #[Test]
    public function testCurrent(): void
    {
        $stream = self::createStream('abc');
        $input = new StreamInput($stream);

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
        $stream = self::createStream('abc');
        $input = new StreamInput($stream);

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
        $stream = self::createStream('abc');
        $input = new StreamInput($stream);

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
        $stream = self::createStream('abc');
        $input = new StreamInput($stream);

        $this->assertSame('bc', $input->slice(1, 3));
    }

    /** @return resource */
    private static function createStream(string $string)
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        rewind($stream);

        return $stream;
    }
}
