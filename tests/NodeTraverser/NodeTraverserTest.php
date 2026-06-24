<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\NodeTraverser;

use Override;
use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Statements;
use Package\NodeTraverser\AbstractNodeVisitor;
use Package\NodeTraverser\NodeTraverser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NodeTraverserTest extends TestCase
{
    #[Test]
    public function testTraverse(): void
    {
        $literal = new Literals\NullLiteral(1);
        $expression = new Expressions\ArrayExpression(1, [$literal]);
        $statement = new Statements\ReturnStatement(1, $expression);

        $visitor = new NodeVisitor;
        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor);

        $this->assertSame(
            actual: $traverser->traverse([$statement]),
            expected: [$statement],
        );

        $this->assertSame(
            actual: $visitor->trace,
            expected: [
                ['beforeTraverse', [$statement]],
                ['enterNode', $statement],
                ['enterNode', $expression],
                ['beforeTraverse', [$literal]],
                ['enterNode', $literal],
                ['leaveNode', $literal],
                ['afterTraverse', [$literal]],
                ['leaveNode', $expression],
                ['leaveNode', $statement],
                ['afterTraverse', [$statement]],
            ],
        );
    }
}

class NodeVisitor extends AbstractNodeVisitor
{
    public array $trace = [];

    #[Override]
    public function beforeTraverse(array $nodes)
    {
        return $this->traceEvent('beforeTraverse', $nodes);
    }

    #[Override]
    public function enterNode(AbstractNode $node)
    {
        return $this->traceEvent('enterNode', $node);
    }

    #[Override]
    public function leaveNode(AbstractNode $node)
    {
        return $this->traceEvent('leaveNode', $node);
    }

    #[Override]
    public function afterTraverse(array $nodes)
    {
        return $this->traceEvent('afterTraverse', $nodes);
    }

    private function traceEvent(string $method, mixed $value)
    {
        $this->trace[] = [$method, $value];

        return $value;
    }
}
