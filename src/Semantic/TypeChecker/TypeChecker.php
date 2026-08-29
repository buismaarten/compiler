<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Package\Exceptions\Semantic\TypeChecker\TypeMismatchException;
use Package\Nodes\AbstractNode;
use Package\Semantic\Symbols\AbstractSymbol;

final class TypeChecker
{
    /** @var iterable<AbstractNode, AbstractSymbol> */
    private iterable $table;

    /** @param iterable<AbstractNode, AbstractSymbol> $table */
    public function __construct(iterable $table)
    {
        $this->table = $table;
    }

    public function check(): void
    {
        foreach ($this->table as $node => $symbol) {
            throw new TypeMismatchException($node->location->startLine);
        }
    }
}
