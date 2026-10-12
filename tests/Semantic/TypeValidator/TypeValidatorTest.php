<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeValidator;

use Package\Parser\Parser;
use Package\Semantic\TypeValidator\TypeValidator;
use Package\Tests\Testing\SnapshotAssertions;
use Package\Tokenizer\Tokenizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TypeValidatorTest extends TestCase
{
    use SnapshotAssertions;

    #[Test]
    public function testSnapshots(): void
    {
        $this->assertSnapshot(__DIR__.'/__snapshots__/*.txt', function (string $input): void {
            $tokenizer = new Tokenizer($input);
            $parser = new Parser($tokenizer);
            $typeValidator = new TypeValidator($parser->parse());
        });
    }
}
