<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Resolver\Module;

use Override;
use Package\Nodes\AbstractNode;
use Package\Nodes\Statements;
use Package\NodeTraverser\AbstractNodeVisitor;

final class ModuleCollector extends AbstractNodeVisitor
{
    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    #[Override]
    public function enterNode(AbstractNode $node)
    {
        if ($node instanceof Statements\ImportStatement) {
            // TODO
        }
    }
}
