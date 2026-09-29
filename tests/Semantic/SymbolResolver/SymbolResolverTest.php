<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\SymbolResolver;

use Package\Parser\Parser;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Tests\Testing\SnapshotAssertions;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SymbolResolverTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testFixtures(): void
    {
        $this->assertMatchesFixtures(__DIR__.'/__fixtures__/*.txt', function (string $input) {
            $tokenizer = new Tokenizer($input);
            $parser = new Parser($tokenizer);

            $node = $parser->parse();
            $symbolResolver = new SymbolResolver($node);

            return $symbolResolver->getSymbols();
        });
    }
}
