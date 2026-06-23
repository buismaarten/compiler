<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\NodeTraverser;

use Package\Nodes\AbstractNode;
use ReflectionClass;
use ReflectionProperty;

final class NodeTraverser
{
    /** @var array<class-string<AbstractNode>, ReflectionProperty[]> */
    private static array $cache = [];

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
        foreach ($this->visitors as $visitor) {
            if (($return = $visitor->beforeTraverse($nodes)) !== null) {
                $nodes = $return;
            }
        }

        foreach ($nodes as $i => $node) {
            foreach ($this->visitors as $visitor) {
                if (($return = $visitor->enterNode($node)) !== null) {
                    $node = $return;
                    $nodes[$i] = $node;
                }

                $this->traverseNode($node);

                if (($return = $visitor->leaveNode($node)) !== null) {
                    $node = $return;
                    $nodes[$i] = $node;
                }
            }
        }

        foreach ($this->visitors as $visitor) {
            if (($return = $visitor->afterTraverse($nodes)) !== null) {
                $nodes = $return;
            }
        }

        return $nodes;
    }

    private function traverseNode(AbstractNode $node): void
    {
        self::$cache[$node::class] ??= new ReflectionClass($node)->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach (self::$cache[$node::class] as $property) {
            $name = $property->getName();
            $subNode = $node->{$name};

            if (is_array($subNode)) {
                /** @var AbstractNode[] $subNode */
                $node->{$name} = $this->traverse($subNode);
            }

            if ($subNode instanceof AbstractNode) {
                foreach ($this->visitors as $visitor) {
                    if (($return = $visitor->enterNode($subNode)) !== null) {
                        $subNode = $return;
                        $node->{$name} = $subNode;
                    }

                    $this->traverseNode($subNode);

                    if (($return = $visitor->leaveNode($subNode)) !== null) {
                        $subNode = $return;
                        $node->{$name} = $subNode;
                    }
                }
            }
        }
    }
}
