<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\NodeTraverser;

use Package\Nodes\AbstractNode;
use Package\Nodes\Literals;
use Package\Nodes\Statements;
use Package\NodeTraverser\NodeTraverser;
use Package\NodeTraverser\NodeVisitor;
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
            expected: [$statement, $literal],
        );
    }
}

class DummyNodeVisitor implements NodeVisitor
{
    /** @var AbstractNode[] */
    public array $trace = [];

    public function visit(AbstractNode $node): AbstractNode
    {
        $this->trace[] = $node;

        return $node;
    }
}
