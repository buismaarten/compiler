<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Nodes\AbstractType;

final class NamedType extends AbstractType
{
    public readonly string $name;

    public function __construct(int $line, string $name)
    {
        parent::__construct($line);

        $this->name = $name;
    }
}
