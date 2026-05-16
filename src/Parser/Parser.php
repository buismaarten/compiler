<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Parser;

use Buismaarten\Parser\Exceptions\Parser\ExpectedTokenException;
use Buismaarten\Parser\Exceptions\Parser\UnexpectedEndOfInputException;
use Buismaarten\Parser\Lexer\LexerInterface;
use Buismaarten\Parser\Nodes\BinaryNode;
use Buismaarten\Parser\Nodes\NodeInterface;
use Buismaarten\Parser\Nodes\NumberNode;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Override;

final class Parser implements ParserInterface
{
    private LexerInterface $lexer;
    private ?Token $current = null;

    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
        $this->current = $this->lexer->next();
    }

    #[Override]
    public function parse(): NodeInterface
    {
        if ($this->current === null) {
            throw new UnexpectedEndOfInputException;
        }

        return $this->parseExpression();
    }

    private function parseExpression(): NodeInterface
    {
        $node = $this->parseTerm();

        while ($this->current?->type === TokenType::T_PLUS) {
            $operator = $this->expect(TokenType::T_PLUS);
            $node = new BinaryNode($node, $operator->type->name, $this->parseTerm());
        }

        return $node;
    }

    private function parseTerm(): NodeInterface
    {
        $node = $this->parseFactor();

        while ($this->current?->type === TokenType::T_MULTIPLY) {
            $operator = $this->expect(TokenType::T_MULTIPLY);
            $node = new BinaryNode($node, $operator->type->name, $this->parseFactor());
        }

        return $node;
    }

    private function parseFactor(): NumberNode
    {
        $token = $this->expect(TokenType::T_NUMBER);
        $value = intval($token->value);

        return new NumberNode($value);
    }

    private function expect(TokenType $type): Token
    {
        $token = $this->current;

        if ($token === null) {
            throw new UnexpectedEndOfInputException($type->name);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->current = $this->lexer->next();

        return $token;
    }
}
