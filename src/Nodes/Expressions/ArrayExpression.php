<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Core\Location;

final class ArrayExpression extends AbstractExpression
{
    /** @var AbstractExpression[] */
    public array $expressions;

    /** @param AbstractExpression[] $expressions */
    public function __construct(Location $location, array $expressions)
    {
        parent::__construct($location);

        $this->expressions = $expressions;
    }
}
