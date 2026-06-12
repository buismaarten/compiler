<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Input;

use InvalidArgumentException;
use Override;

final class StreamInput implements InputInterface
{
    use InputTrait;

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
        if (($current = fgetc($this->stream)) !== false) {
            return $current;
        }

        return null;
    }

    #[Override]
    public function slice(int $start, int $end): string
    {
        fseek($this->stream, $start);

        // @phpstan-ignore-next-line
        if (($string = fread($this->stream, $end - $start)) !== false) {
            return $string;
        }

        return '';
    }
}
