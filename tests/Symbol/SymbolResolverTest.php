<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Symbol;

use Package\Parser\Parser;
use Package\Parser\ParserInterface;
use Package\Symbol\SymbolResolver;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SymbolResolverTest extends TestCase
{
    #[Test]
    public function testTodo(): void
    {
        $statements = $this->createParser('var a: int = 1; if (a) { var b: int = 1; }')->parse();

        $resolver = new SymbolResolver;
        $resolver->traverse($statements);
    }

    private static function createParser(string $input): ParserInterface
    {
        $tokenizer = new Tokenizer($input);
        $parser = new Parser($tokenizer);

        return $parser;
    }
}
