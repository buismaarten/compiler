<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Package\Nodes\AbstractStatement;
use Package\NodeTraverser\NodeTraverser;

final class SymbolResolver
{
    /** @param AbstractStatement[] $statements */
    public function resolve(array $statements): void
    {
        $visitor = new SymbolCollector;

        $traverser = new NodeTraverser($visitor);
        $traverser->traverse($statements);
    }
}
