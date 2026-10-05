<?php

ddeclare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

final class TupleType extends AbstractType
{
    /** @var AbstractType[] */
    public array $types;

    /** @param AbstractType[] $types */
    public function __construct(array $types)
    {
        $this->types = $types;
    }
}
