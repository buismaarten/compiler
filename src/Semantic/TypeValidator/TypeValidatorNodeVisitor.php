<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\TypeValidator;

use Override;
use Package\Common\Language\Primitive;
use Package\Exceptions\Semantic\TypeValidator\TypeErrorException;
use Package\Nodes;
use Package\Nodes\AbstractNode;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\Semantic\TypeResolver\TypeResolver;
use Package\Types;

final class TypeValidatorNodeVisitor extends AbstractNodeVisitor
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

        if ($node instanceof Nodes\Expressions\UnaryExpression) {
            $this->enterUnaryExpression($node);
            return;
        }

        if ($node instanceof Nodes\Statements\ConstantStatement) {
            $this->enterConstantStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\FunctionStatement) {
            $this->enterFunctionStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\IfStatement) {
            $this->enterIfStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\PrintStatement) {
            $this->enterPrintStatement($node);
            return;
        }

        if ($node instanceof Nodes\Statements\VariableStatement) {
            $this->enterVariableStatement($node);
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

        if ($type instanceof Types\ErrorType) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterConstantStatement(Nodes\Statements\ConstantStatement $node): void
    {
        // TODO
        // $type = $this->typeResolver->resolveType($node->expression);
    }

    private function enterFunctionStatement(Nodes\Statements\FunctionStatement $node): void
    {
        // TODO
        // $types = $this->typeResolver->resolveFunctionReturnTypes($node);
    }

    private function enterIfStatement(Nodes\Statements\IfStatement $node): void
    {
        $type = $this->typeResolver->resolveType($node->condition);

        if (!$type instanceof Types\PrimitiveType || $type->primitive !== Primitive::Bool) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterPrintStatement(Nodes\Statements\PrintStatement $node): void
    {
        $type = $this->typeResolver->resolveType($node->expression);
        $type = Types\NullableType::unwrap($type);

        if (!$type instanceof Types\PrimitiveType) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterUnaryExpression(Nodes\Expressions\UnaryExpression $node): void
    {
        $type = $this->typeResolver->resolveType($node);

        if ($type instanceof Types\ErrorType) {
            throw new TypeErrorException($node->getStartLine());
        }
    }

    private function enterVariableStatement(Nodes\Statements\VariableStatement $node): void
    {
        // TODO
        // $type = $this->typeResolver->resolveType($node->expression);
    }

    private function enterWhileStatement(Nodes\Statements\WhileStatement $node): void
    {
        $type = $this->typeResolver->resolveType($node->condition);

        if (!$type instanceof Types\PrimitiveType || $type->primitive !== Primitive::Bool) {
            throw new TypeErrorException($node->getStartLine());
        }
    }
}
