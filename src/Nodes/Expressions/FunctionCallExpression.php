<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Nodes\Expressions;

use Package\Nodes\AbstractExpression;

final class FunctionCallExpression extends AbstractExpression
{
    public readonly string $name;

    /** @var AbstractExpression[] */
    public readonly array $arguments;

    /** @param AbstractExpression[] $arguments */
    public function __construct(int $line, string $name, array $arguments)
    {
        parent::__construct($line);

        $this->name = $name;
        $this->arguments = $arguments;
    }
}
