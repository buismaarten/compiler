<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

use Package\Core\Location;

final class Token
{
    public readonly Location $location;
    public readonly TokenType $type;
    public readonly string $value;

    public function __construct(Location $location, TokenType $type, string $value = '')
    {
        $this->location = $location;
        $this->type = $type;
        $this->value = $value;
    }
}
