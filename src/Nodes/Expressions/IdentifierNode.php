<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class IdentifierNode extends AbstractExpression
{
    public function __construct(
        public readonly string $value,
    ) {}
}
