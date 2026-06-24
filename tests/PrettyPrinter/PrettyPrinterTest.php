<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\PrettyPrinter;

use Package\Nodes\Expressions;
use Package\Nodes\Literals;
use Package\Nodes\Parameter;
use Package\Nodes\Statements;
use Package\Nodes\Types;
use Package\PrettyPrinter\PrettyPrinter;
use Package\PrettyPrinter\PrettyPrinterInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PrettyPrinterTest extends TestCase
{
    #[Test]
    public function testBinaryExpression(): void
    {
        $this->assertSame(
            actual: self::createPrettyPrinter()->prettyPrintExpression(
                new Expressions\BinaryExpression(
                    line: 1,
                    operator: Expressions\BinaryOperator::Plus,
                    left: new Literals\IntegerLiteral(
                        line: 1,
                        value: 1,
                    ),
                    right: new Expressions\BinaryExpression(
                        line: 1,
                        operator: Expressions\BinaryOperator::Multiply,
                        left: new Literals\IntegerLiteral(
                            line: 1,
                            value: 2,
                        ),
                        right: new Literals\IntegerLiteral(
                            line: 1,
                            value: 3,
                        ),
                    ),
                ),
            ),
            expected: '1 + 2 * 3',
        );

        $this->assertSame(
            actual: self::createPrettyPrinter()->prettyPrintExpression(
                new Expressions\BinaryExpression(
                    line: 1,
                    operator: Expressions\BinaryOperator::Multiply,
                    left: new Expressions\BinaryExpression(
                        line: 1,
                        operator: Expressions\BinaryOperator::Plus,
                        left: new Literals\IntegerLiteral(
                            line: 1,
                            value: 1,
                        ),
                        right: new Literals\IntegerLiteral(
                            line: 1,
                            value: 2,
                        ),
                    ),
                    right: new Literals\IntegerLiteral(
                        line: 1,
                        value: 3,
                    ),
                ),
            ),
            expected: '(1 + 2) * 3',
        );

        $this->assertSame(
            actual: self::createPrettyPrinter()->prettyPrintExpression(
                new Expressions\BinaryExpression(
                    line: 1,
                    operator: Expressions\BinaryOperator::Minus,
                    left: new Literals\IntegerLiteral(
                        line: 1,
                        value: 1,
                    ),
                    right: new Expressions\BinaryExpression(
                        line: 1,
                        operator: Expressions\BinaryOperator::Minus,
                        left: new Literals\IntegerLiteral(
                            line: 1,
                            value: 2,
                        ),
                        right: new Literals\IntegerLiteral(
                            line: 1,
                            value: 3,
                        ),
                    ),
                ),
            ),
            expected: '1 - (2 - 3)',
        );
    }

    #[Test]
    public function testFunctionStatement(): void
    {
        $this->assertSame(
            actual: self::createPrettyPrinter()->prettyPrint([
                new Statements\FunctionStatement(
                    line: 1,
                    name: 'sum',
                    type: new Types\NamedType(
                        line: 1,
                        name: 'int',
                    ),
                    parameters: [
                        new Parameter(
                            line: 1,
                            name: 'a',
                            type: new Types\NamedType(
                                line: 1,
                                name: 'int',
                            ),
                        ),
                        new Parameter(
                            line: 1,
                            name: 'b',
                            type: new Types\NamedType(
                                line: 1,
                                name: 'int',
                            ),
                        ),
                    ],
                    statements: [
                        new Statements\ReturnStatement(
                            line: 1,
                            expression: new Expressions\BinaryExpression(
                                line: 1,
                                operator: Expressions\BinaryOperator::Plus,
                                left: new Expressions\IdentifierExpression(
                                    line: 1,
                                    name: 'a',
                                ),
                                right: new Expressions\IdentifierExpression(
                                    line: 1,
                                    name: 'b',
                                ),
                            ),
                        ),
                    ],
                ),
            ]),
            expected: "fn sum(a: int, b: int): int {\n    return a + b;\n}",
        );
    }

    private static function createPrettyPrinter(): PrettyPrinterInterface
    {
        return new PrettyPrinter;
    }
}
