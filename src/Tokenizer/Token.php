<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tokenizer;

use Package\Common\Location;

final readonly class Token
{
    public Location $location;
    public TokenType $type;
    public string $value;

    public function __construct(Location $location, TokenType $type, string $value = '')
    {
        $this->location = $location;
        $this->type = $type;
        $this->value = $value;
    }
}
