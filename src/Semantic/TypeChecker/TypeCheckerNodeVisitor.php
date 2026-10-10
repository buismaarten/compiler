<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeChecker;

use Override;
use Package\Common\Language\Primitive;
use Package\Exceptions\Semantic\TypeChecker\TypeErrorException;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\TypeResolver\TypeResolver;
use Package\Semantic\Types\ErrorType;
use Package\Types;

final class TypeCheckerNodeVisitor extends AbstractNodeVisitor
{
    private TypeResolver $typeResolver;

    public function __construct(TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        if ($node instanceof Nodes\Expressions\BinaryExpression) {
            $this->enterBinaryExpression($node);
            return;
        }

        if ($node instanceof Nodes\Statements\IfStatement) {
            $this->enterIfStatement($node);
            return;
        }

        if ($node instanceof Nodes\Expressions\UnaryExpression) {
            $this->enterUnaryExpression($node);
            return;
        }

        if ($node instanceof Nodes\Statements\WhileStatement) {
            $this->enterWhileStatement($node);
            return;
        }
    }

    private function enterBinaryExpression(Nodes\Expressions\BinaryExpression $node): void
    {
        $type = $this->typeResolver->resolveType($node);

        if ($type instanceof ErrorType) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterIfStatement(Nodes\Statements\IfStatement $node): void
    {
        $type = $this->typeResolver->resolveType($node->condition);

        if (!$type instanceof Types\PrimitiveType || $type->primitive !== Primitive::Bool) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterUnaryExpression(Nodes\Expressions\UnaryExpression $node): void
    {
        $type = $this->typeResolver->resolveType($node);

        if ($type instanceof ErrorType) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterWhileStatement(Nodes\Statements\WhileStatement $node): void
    {
        $type = $this->typeResolver->resolveType($node->condition);

        if (!$type instanceof Types\PrimitiveType || $type->primitive !== Primitive::Bool) {
            throw new TypeErrorException($node->getStartLine());
        }
    }
}
