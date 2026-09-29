<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeResolver;

use Package\Tokenizer\Tokenizer;
use Package\Parser\Parser;
use Package\Semantic\SymbolResolver\SymbolResolver;
use Package\Semantic\TypeResolver\TypeResolver;
use Package\Tests\Testing\SnapshotAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testSnapshots(): void
    {
        $this->assertMatchesSnapshot(__DIR__.'/__snapshots__/*.txt', function (string $input) {
            $tokenizer = new Tokenizer($input);
            $parser = new Parser($tokenizer);
            $typeResolver = new TypeResolver($parser->parse());

            return [
                'functionReturnTypes' => $typeResolver->getFunctionReturnTypes(),
                'types' => $typeResolver->getTypes(),
            ];
        });
    }
}
