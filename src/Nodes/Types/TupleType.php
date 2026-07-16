<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Types;

use Package\Core\Location;
use Package\Nodes\AbstractType;

final class TupleType extends AbstractType
{
    /** @var AbstractType[] */
    public array $types;

    /** @param AbstractType[] $types */
    public function __construct(Location $location, array $types)
    {
        parent::__construct($location);

        $this->types = $types;
    }
}
