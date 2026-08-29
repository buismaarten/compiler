<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Semantic\TypeChecker;

use Package\Core\Location;
use Package\Exceptions\Semantic\TypeChecker\TypeMismatchException;
use Package\Language\BuiltinTypeKind;
use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Semantic\Symbols;
use Package\Semantic\TypeChecker\TypeChecker;
use Package\Semantic\Types;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WeakMap;

// TODO
class TypeCheckerTest extends TestCase
{
    #[Test]
    public function test(): void
    {
        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessageIs('Type mismatch on line -1');

        $node = new Expressions\AssignmentExpression(
            location: Location::dummy(),
            name: 'foo',
            expression: new Literals\IntegerLiteral(
                location: Location::dummy(),
                value: 42,
            ),
        );

        $symbol = new Symbols\ValueSymbol(
            location: Location::dummy(),
            name: 'foo',
            type: new Types\BuiltinType(BuiltinTypeKind::Integer),
            kind: Symbols\ValueSymbolKind::Variable,
        );

        $mapping = new WeakMap;
        $mapping[$node] = $symbol;

        $typeChecker = new TypeChecker($mapping);
        $typeChecker->check();
    }
}
