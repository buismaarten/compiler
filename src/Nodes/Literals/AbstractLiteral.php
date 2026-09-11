<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Literals;

use Package\Language\Primitive;
use Package\Nodes\Expressions\AbstractExpression;

/** @template T */
abstract class AbstractLiteral extends AbstractExpression
{
    /** @var T */
    protected mixed $value;

    /** @return T */
    public function getValue(): mixed
    {
        return $this->value;
    }

    abstract public function getPrimitive(): Primitive;
}
