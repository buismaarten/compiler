<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeChecker;

use Package\Semantic\TypeChecker\TypeChecker;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WeakMap;

class TypeCheckerTest extends TestCase
{
    #[Test]
    public function testTodo(): void
    {
        $this->expectNotToPerformAssertions();

        $mapping = new WeakMap;

        $typeChecker = new TypeChecker($mapping);
        $typeChecker->validate();
    }
}
