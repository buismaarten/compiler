<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractLiteral;

final class TupleLiteral extends AbstractLiteral
{
    /** @var AbstractExpression[] */
    public readonly array $expressions;

    /** @param AbstractExpression[] $expressions */
    public function __construct(int $line, array $expressions)
    {
        parent::__construct($line);

        $this->expressions = $expressions;
    }
}
