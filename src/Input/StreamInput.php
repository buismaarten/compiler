<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use Exception;
use Override;

final class StreamInput implements InputInterface
{
    private int $offset = 0;

    /** @var resource */
    private $stream;

    /** @param resource $stream */
    public function __construct($stream)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new Exception('Stream must be a valid resource of type stream.');
        }

        $this->stream = $stream;
    }

    #[Override]
    public function current(): ?string
    {
        if (($character = fgetc($this->stream)) !== false) {
            return $character;
        }

        return null;
    }

    #[Override]
    public function next(): void
    {
        $this->offset++;
    }

    #[Override]
    public function offset(): int
    {
        return $this->offset;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        fseek($this->stream, $start);

        return fread($this->stream, $end - $start);
    }
}
