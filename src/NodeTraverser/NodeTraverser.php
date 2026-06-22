<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;

final class NodeTraverser
{
    /** @var NodeVisitor[] */
    private array $visitors = [];

    public function addVisitor(NodeVisitor $visitor): void
    {
        $this->visitors[] = $visitor;
    }

    /**
     * @param AbstractNode[] $nodes
     * @return AbstractNode[]
     */
    public function traverse(array $nodes): array
    {
        foreach ($nodes as $i => $node) {
            foreach ($this->visitors as $visitor) {
                $node = $visitor->visit($node);
                $nodes[$i] = $node;

                $this->traverseNode($node);
            }
        }

        return $nodes;
    }

    private function traverseNode(AbstractNode $node): void
    {
        foreach (Reflection::getClassProperties($node) as $property) {
            $name = $property->getName();
            $subNode = $node->{$name};

            if (is_array($subNode)) {
                /** @var AbstractNode[] $subNode */
                $node->{$name} = $this->traverse($subNode);
            }

            if ($subNode instanceof AbstractNode) {
                foreach ($this->visitors as $visitor) {
                    $subNode = $visitor->visit($subNode);
                    $node->{$name} = $subNode;

                    $this->traverseNode($subNode);
                }
            }
        }
    }
}
