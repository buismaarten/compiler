<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use InvalidArgumentException;
use Override;

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
        fseek($this->stream, $offset);

        if ($current !== false) {
            return $current;
        }

        // Fallback
        return null;
    }

    #[Override]
    public function next(): void
    {
        fseek($this->stream, 1, SEEK_CUR);
    }

    #[Override]
    public function offset(): int
    {
        if (($offset = ftell($this->stream)) !== false) {
            return $offset;
        }

        // Fallback
        return 0;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        fseek($this->stream, $start);

        // @phpstan-ignore-next-line
        if (($string = fread($this->stream, $end - $start)) !== false) {
            return $string;
        }

        // Fallback
        return '';
    }
}
