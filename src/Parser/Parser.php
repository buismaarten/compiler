<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Parser;

use Buismaarten\Parser\Exceptions\Parser\ExpectedTokenException;
use Buismaarten\Parser\Exceptions\Parser\UnexpectedEndOfInputException;
use Buismaarten\Parser\Lexer\LexerInterface;
use Buismaarten\Parser\Nodes\AbstractNode;
use Buismaarten\Parser\Nodes\BinaryNode;
use Buismaarten\Parser\Nodes\NumberNode;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Override;

final class Parser implements ParserInterface
{
    private LexerInterface $lexer;
    private Token $current;

    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
        $this->current = $this->lexer->next();
    }

    #[Override]
    public function parse(): AbstractNode
    {
        $node = $this->parseTerm();

        while ($this->current->type === TokenType::T_PLUS) {
            $operator = $this->expect(TokenType::T_PLUS);
            $node = new BinaryNode($operator->type->name, $node, $this->parseTerm());
        }

        return $node;
    }

    private function parseTerm(): AbstractNode
    {
        $node = $this->parseFactor();

        while ($this->current->type === TokenType::T_MULTIPLY) {
            $operator = $this->expect(TokenType::T_MULTIPLY);
            $node = new BinaryNode($operator->type->name, $node, $this->parseFactor());
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

        if ($token->type === TokenType::T_EOF) {
            throw new UnexpectedEndOfInputException($token->line);
        }

        if ($token->type !== $type) {
            throw new ExpectedTokenException($token->line, $type->name, $token->type->name);
        }

        $this->current = $this->lexer->next();

        return $token;
    }
}
