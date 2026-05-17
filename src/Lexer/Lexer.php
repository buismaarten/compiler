<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Buismaarten\Parser\Lexer;

use Buismaarten\Parser\Exceptions\Lexer\UnexpectedCharacterException;
use Buismaarten\Parser\Input\Input;
use Buismaarten\Parser\Input\InputInterface;
use Buismaarten\Parser\Token\Token;
use Buismaarten\Parser\Token\TokenType;
use Override;

final class Lexer implements LexerInterface
{
    private InputInterface $input;
    private int $line = 1;

    public function __construct(InputInterface|string $input)
    {
        if (is_string($input)) {
            $input = new Input($input);
        }

        $this->input = $input;
    }

    #[Override]
    public function next(): Token
    {
        while (($current = $this->input->current()) !== null && ctype_space($current)) {
            $this->input->next();

            if ($current === "\n") {
                $this->line++;
            }
        }

        if (($current = $this->input->current()) === null) {
            return new Token(TokenType::T_EOF, $this->line);
        }

        return match ($current) {
            '*' => $this->tokenizeSingle(TokenType::T_MULTIPLY),
            '+' => $this->tokenizeSingle(TokenType::T_PLUS),

            default => match (true) {
                ctype_alpha($current) => $this->tokenizeIdentifier(),
                ctype_digit($current) => $this->tokenizeNumber(),

                // Fallback
                default => throw new UnexpectedCharacterException($this->line, $current),
            },
        };
    }

    private function tokenizeIdentifier(): Token
    {
        return $this->tokenizePredicate(
            type: TokenType::T_IDENTIFIER,
            predicate: static fn (string $character) => ctype_alnum($character),
        );
    }

    private function tokenizeNumber(): Token
    {
        return $this->tokenizePredicate(
            type: TokenType::T_NUMBER,
            predicate: static fn (string $character) => ctype_digit($character),
        );
    }

    /** @param callable(string): bool $predicate */
    private function tokenizePredicate(TokenType $type, callable $predicate): Token
    {
        $start = $this->input->offset();

        while (($current = $this->input->current()) !== null && $predicate($current)) {
            $this->input->next();
        }

        return new Token(
            type: $type,
            line: $this->line,
            value: $this->input->slice($start, $this->input->offset()),
        );
    }

    private function tokenizeSingle(TokenType $type): Token
    {
        $this->input->next();

        return new Token($type, $this->line);
    }

    #[Override]
    public function getTokens(): iterable
    {
        while (($token = $this->next())->type !== TokenType::T_EOF) {
            yield $token;
        }
    }
}
