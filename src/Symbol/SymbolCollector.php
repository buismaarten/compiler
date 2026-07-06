<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Symbol;

use Override;
use Package\Exceptions\Symbol\UndefinedSymbolException;
use Package\Nodes\AbstractNode;
use Package\Nodes\Block;
use Package\Nodes\Expressions;
use Package\Nodes\Statements;
use Package\NodeTraverser\AbstractNodeVisitor;
use SplObjectStorage;

final class SymbolCollector extends AbstractNodeVisitor
{
    private ?Scope $scope = null;

    /** @var SplObjectStorage<AbstractNode, AbstractNode> */
    private SplObjectStorage $table;

    /** @param SplObjectStorage<AbstractNode, AbstractNode> $table */
    public function __construct(SplObjectStorage $table)
    {
        $this->table = $table;
    }

    #[Override]
    public function enterNode(AbstractNode $node)
    {
        if ($node instanceof Block) {
            $this->scope = new Scope($this->scope);
        }

        if ($node instanceof Expressions\AssignmentExpression) {
            $symbol = $this->findSymbol($node->name, $node);

            if ($symbol === null) {
                throw new UndefinedSymbolException($node->getStartLine(), $node->name);
            }
        }

        if ($node instanceof Expressions\FunctionCallExpression) {
            $symbol = $this->findSymbol($node->name, $node);

            if ($symbol === null) {
                throw new UndefinedSymbolException($node->getStartLine(), $node->name);
            }
        }

        if ($node instanceof Expressions\IdentifierExpression) {
            $symbol = $this->findSymbol($node->name, $node);

            if ($symbol === null) {
                throw new UndefinedSymbolException($node->getStartLine(), $node->name);
            }
        }

        if ($node instanceof Statements\ConstStatement) {
            $this->scope?->addSymbol($node->name, $node);
        }

        if ($node instanceof Statements\FunctionStatement) {
            $this->scope?->addSymbol($node->name, $node);
        }

        if ($node instanceof Statements\VarStatement) {
            $this->scope?->addSymbol($node->name, $node);
        }
    }

    #[Override]
    public function leaveNode(AbstractNode $node)
    {
        if ($node instanceof Block) {
            $this->scope = $this->scope?->parent;
        }
    }

    private function findSymbol(string $name, AbstractNode $node): ?AbstractNode
    {
        for ($scope = $this->scope; $scope !== null; $scope = $scope->parent) {
            $symbol = $scope->symbols[$name] ?? null;

            if ($symbol !== null) {
                return $this->table[$node] ??= $symbol;
            }
        }

        // Fallback
        return null;
    }
}
