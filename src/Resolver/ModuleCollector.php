<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Resolver\Module;

use Override;
use Package\Nodes\AbstractNode;
use Package\Nodes\AbstractStatement;
use Package\Nodes\Statements;
use Package\NodeTraverser\AbstractNodeVisitor;

final class ModuleCollector extends AbstractNodeVisitor
{
    private string $rootPath;

    /** @var array<string, AbstractStatement[]> */
    private array $modules = [];

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /** @return array<string, AbstractStatement[]> */
    public function getModules(): array
    {
        return $this->modules;
    }

    #[Override]
    public function enterNode(AbstractNode $node)
    {
        if ($node instanceof Statements\ImportStatement) {
            // TODO
        }
    }
}
