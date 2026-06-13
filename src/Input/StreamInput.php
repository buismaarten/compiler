<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use InvalidArgumentException;
use Override;
use RuntimeException;

final class StreamInput implements InputInterface
{
    /** @var resource */
    private $stream;

    /** @param resource $stream */
    public function __construct($stream)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidArgumentException('Stream must be a valid resource of type stream.');
        }

        $this->stream = $stream;
    }

    #[Override]
    public function current(): ?string
    {
        if (($offset = ftell($this->stream)) === false) {
            return null;
        }

        $current = fgetc($this->stream);

        if (fseek($this->stream, $offset) !== 0) {
            throw new RuntimeException('Stream is not seekable.');
        }

        if ($current !== false) {
            return $current;
        }

        // Fallback
        return null;
    }

    #[Override]
    public function next(): void
    {
        if (fseek($this->stream, 1, SEEK_CUR) !== 0) {
            throw new RuntimeException('Stream is not seekable.');
        }
    }

    #[Override]
    public function offset(): int
    {
        if (($offset = ftell($this->stream)) === false) {
            throw new RuntimeException('Stream is not seekable.');
        }

        return $offset;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        $offset = $end - $start;

        if ($offset < 1) {
            throw new InvalidArgumentException('End offset must be greater than start offset.');
        }

        if (fseek($this->stream, $start) !== 0) {
            throw new RuntimeException('Stream is not seekable.');
        }

        if (($string = fread($this->stream, $offset)) === false) {
            throw new RuntimeException('Failed to read from stream.');
        }

        return $string;
    }
}
