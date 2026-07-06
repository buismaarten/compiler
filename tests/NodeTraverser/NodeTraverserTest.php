<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\NodeTraverser;

use Override;
use Package\Nodes\AbstractNode;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Location;
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
        $location = new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );

        $literal = new Literals\NullLiteral($location);
        $expression = new Expressions\ArrayExpression($location, [$literal]);
        $statement = new Statements\ReturnStatement($location, $expression);

        $visitor = new NodeVisitor;
        $traverser = new NodeTraverser($visitor);

        $this->assertSame(
            actual: $traverser->traverse($statement),
            expected: $statement,
        );

        $this->assertSame(
            actual: $visitor->trace,
            expected: [
                ['beforeTraverse', $statement],
                ['enterNode', $statement],
                ['enterNode', $expression],
                ['enterNode', $literal],
                ['leaveNode', $literal],
                ['leaveNode', $expression],
                ['leaveNode', $statement],
                ['afterTraverse', $statement],
            ],
        );
    }
}

class NodeVisitor extends AbstractNodeVisitor
{
    public array $trace = [];

    #[Override]
    public function beforeTraverse(AbstractNode $nodes)
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
    public function afterTraverse(AbstractNode $nodes)
    {
        return $this->traceEvent('afterTraverse', $nodes);
    }

    private function traceEvent(string $method, mixed $value)
    {
        $this->trace[] = [$method, $value];

        return $value;
    }
}
