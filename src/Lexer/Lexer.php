<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Lexer;

use Override;
use Package\Exceptions\Lexer\UnexpectedCharacterException;
use Package\Input\Input;
use Package\Input\InputInterface;
use Package\Token\Token;
use Package\Token\TokenType;

final class Lexer implements LexerInterface
{
    private const KEYWORDS = [
        'false' => TokenType::T_KEYWORD_FALSE,
        'null'  => TokenType::T_KEYWORD_NULL,
        'true'  => TokenType::T_KEYWORD_TRUE,
    ];

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
    public function getTokens(): iterable
    {
        while (($token = $this->next())->type !== TokenType::T_EOF) {
            yield $token;
        }
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
            '*' => $this->tokenizeCharacter(TokenType::T_MULTIPLY),
            '+' => $this->tokenizeCharacter(TokenType::T_PLUS),
            '_' => $this->tokenizeIdentifier(),

            default => match (true) {
                ctype_alpha($current) => $this->tokenizeIdentifier(),
                ctype_digit($current) => $this->tokenizeNumber(),

                // Fallback
                default => throw new UnexpectedCharacterException($this->line, $current),
            },
        };
    }

    private function tokenizeCharacter(TokenType $type): Token
    {
        $this->input->next();

        return new Token($type, $this->line);
    }

    private function tokenizeIdentifier(): Token
    {
        $start = $this->input->offset();

        while (($current = $this->input->current()) !== null && ($current === '_' || ctype_alnum($current))) {
            $this->input->next();
        }

        $value = $this->input->slice($start, $this->input->offset());
        $type = self::KEYWORDS[$value] ?? TokenType::T_IDENTIFIER;

        if ($type !== TokenType::T_IDENTIFIER) {
            $value = '';
        }

        return new Token($type, $this->line, $value);
    }

    private function tokenizeNumber(): Token
    {
        return $this->tokenizePredicate(
            type: TokenType::T_NUMBER,
            predicate: static fn (string $character) => ctype_digit($character),
        );
    }

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
}
