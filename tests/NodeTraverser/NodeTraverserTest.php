<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\NodeTraverser;

use Package\Nodes\AbstractNode;
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
        $statement = new Statements\ReturnStatement(1, $literal);

        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor = new DummyNodeVisitor);

        $this->assertSame(
            actual: $traverser->traverse([$statement]),
            expected: [$statement],
        );

        $this->assertSame(
            actual: $visitor->trace,
            expected: [
                ['beforeTraverse', [$statement]],
                ['enterNode', $statement],
                ['enterNode', $literal],
                ['leaveNode', $literal],
                ['leaveNode', $statement],
                ['afterTraverse', [$statement]]
            ],
        );
    }
}

class DummyNodeVisitor extends AbstractNodeVisitor
{
    public array $trace = [];

    public function beforeTraverse(array $nodes)
    {
        return $this->traceEvent('beforeTraverse', $nodes);
    }

    public function enterNode(AbstractNode $node)
    {
        return $this->traceEvent('enterNode', $node);
    }

    public function leaveNode(AbstractNode $node)
    {
        return $this->traceEvent('leaveNode', $node);
    }

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
