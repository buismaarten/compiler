<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\PrettyPrinter;

use Package\Nodes\AbstractExpression;
use Package\Nodes\AbstractStatement;

interface PrettyPrinterInterface
{
    /** @param AbstractStatement[] $statements */
    public function prettyPrint(array $statements): string;

    public function prettyPrintExpression(AbstractExpression $expression): string;
}
