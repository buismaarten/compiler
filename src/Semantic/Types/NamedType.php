<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

final class NamedType extends AbstractType
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
