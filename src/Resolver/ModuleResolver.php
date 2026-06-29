<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Resolver\Module;

use Package\Nodes\AbstractStatement;
use Package\NodeTraverser\NodeTraverser;

final class ModuleResolver
{
    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @param AbstractStatement[] $statements
     * @return array<string, AbstractStatement[]>
     */
    public function resolve(array $statements): array
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor = new ModuleCollector($this->rootPath));
        $traverser->traverse($statements);

        return $visitor->getModules();
    }
}
