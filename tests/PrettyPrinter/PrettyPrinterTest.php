<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\PrettyPrinter;

use Package\Parser\Parser;
use Package\PrettyPrinter\PrettyPrinter;
use Package\PrettyPrinter\PrettyPrinterInterface;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PrettyPrinterTest extends TestCase
{
    #[Test]
    public function testBinaryExpression(): void
    {
        $this->assertSame(
            actual: self::print('1 + 2 * 3;'),
            expected: '1 + 2 * 3;',
        );

        $this->assertSame(
            actual: self::print('(1 + 2) * 3;'),
            expected: '(1 + 2) * 3;',
        );

        $this->assertSame(
            actual: self::print('1 - (2 - 3);'),
            expected: '1 - (2 - 3);',
        );
    }

    #[Test]
    public function testFunctionStatement(): void
    {
        $this->assertSame(
            actual: self::print('fn sum(a: int, b: int): int { return a + b; }'),
            expected: "fn sum(a: int, b: int): int {\n    return a + b;\n}",
        );
    }

    private static function print(string $input): string
    {
        $tokenizer = new Tokenizer($input);
        $parser = new Parser($tokenizer);
        $prettyPrinter = new PrettyPrinter;

        return $prettyPrinter->print($parser->parse());
    }

    private static function createPrettyPrinter(): PrettyPrinterInterface
    {
        return new PrettyPrinter;
    }
}
