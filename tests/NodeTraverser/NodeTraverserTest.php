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
        $location = self::createLocation();

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

    private static function createLocation(): Location
    {
        return new Location(
            startLine: -1,
            startOffset: -1,
            endLine: -1,
            endOffset: -1,
        );
    }
}

class NodeVisitor extends AbstractNodeVisitor
{
    public array $trace = [];

    #[Override]
    public function beforeTraverse(AbstractNode $node)
    {
        $this->traceEvent('beforeTraverse', $node);
    }

    #[Override]
    public function enterNode(AbstractNode $node): void
    {
        $this->traceEvent('enterNode', $node);
    }

    #[Override]
    public function leaveNode(AbstractNode $node): void
    {
        $this->traceEvent('leaveNode', $node);
    }

    #[Override]
    public function afterTraverse(AbstractNode $node): void
    {
        $this->traceEvent('afterTraverse', $node);
    }

    private function traceEvent(string $method, mixed $value)
    {
        $this->trace[] = [$method, $value];

        return $value;
    }
}
