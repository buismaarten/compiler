<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\PrettyPrinter;

use Package\Parser\Parser;
use Package\PrettyPrinter\PrettyPrinter;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PrettyPrinterTest extends TestCase
{
    #[Test]
    public function testPrintUsesBinaryPrecedence(): void
    {
        $this->assertSame(
            '1 + 2 * 3;',
            self::print('1 + 2 * 3;'),
        );

        $this->assertSame(
            '(1 + 2) * 3;',
            self::print('(1 + 2) * 3;'),
        );

        $this->assertSame(
            '1 - (2 - 3);',
            self::print('1 - (2 - 3);'),
        );
    }

    #[Test]
    public function testPrintFunctionStatement(): void
    {
        $code = <<<'CODE'
        fn sum(a: int, b: int): int {
            return a + b;
        }
        CODE;

        $this->assertSame(
            $code,
            self::print('fn sum(a: int, b: int): int { return a + b; }'),
        );
    }

    private static function print(string $source): string
    {
        $statements = new Parser(new Tokenizer($source))->parse();

        return (new PrettyPrinter)->print($statements);
    }
}
