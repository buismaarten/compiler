<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Parser;

use Package\Lexer\Lexer;
use Package\Nodes\BinaryNode;
use Package\Nodes\IdentifierNode;
use Package\Nodes\NumberNode;
use Package\Parser\Parser;
use Package\Parser\ParserInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    #[Test]
    public function testBinary(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo + 123 * bar')->parse(),
            expected: new BinaryNode(
                operator: 'T_PLUS',
                left: new IdentifierNode('foo'),
                right: new BinaryNode(
                    operator: 'T_MULTIPLY',
                    left: new NumberNode(123),
                    right: new IdentifierNode('bar'),
                ),
            ),
        );
    }

    #[Test]
    public function testIdentifier(): void
    {
        $this->assertEquals(
            actual: self::createParser('foo')->parse(),
            expected: new IdentifierNode('foo'),
        );
    }

    #[Test]
    public function testNumber(): void
    {
        $this->assertEquals(
            actual: self::createParser('123')->parse(),
            expected: new NumberNode(123),
        );
    }

    private static function createParser(string $input): ParserInterface
    {
        $lexer = new Lexer($input);
        $parser = new Parser($lexer);

        return $parser;
    }
}
