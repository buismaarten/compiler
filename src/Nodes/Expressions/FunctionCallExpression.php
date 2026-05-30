<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class FunctionCallExpression extends AbstractExpression
{
    public function __construct(
        public readonly int $line,
        public readonly string $name,

        /** @var AbstractExpression[] */
        public readonly array $arguments,
    ) {}
}
