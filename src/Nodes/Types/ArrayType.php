<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Nodes\AbstractType;

final class ArrayType extends AbstractType
{
    public readonly AbstractType $type;

    public function __construct(int $line, AbstractType $type)
    {
        parent::__construct($line);

        $this->type = $type;
    }
}
