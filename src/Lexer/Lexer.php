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
        if (!$input instanceof InputInterface) {
            $input = new Input($input);
        }

        $this->input = $input;
    }

    #[Override]
    public function next(): ?Token
    {
        while (($current = $this->input->current()) !== null && ctype_space($current)) {
            $this->input->next();

            if ($current === "\n") {
                $this->line++;
            }
        }

        if (($current = $this->input->current()) === null) {
            return null;
        }

        return match ($current) {
            '+' => $this->tokenizeSingle(TokenType::T_PLUS),
            '*' => $this->tokenizeSingle(TokenType::T_MULTIPLY),

            default => match (true) {
                ctype_digit($current) => $this->tokenizeNumber(),

                // Fallback
                default => throw new UnexpectedCharacterException($this->line, $current),
            },
        };
    }

    private function tokenizeNumber(): Token
    {
        $start = $this->input->position();

        while (($current = $this->input->current()) !== null && ctype_digit($current)) {
            $this->input->next();
        }

        $value = $this->input->slice($start, $this->input->position());

        return new Token(TokenType::T_NUMBER, $this->line, $value);
    }

    private function tokenizeSingle(TokenType $type): Token
    {
        $this->input->next();

        return new Token($type, $this->line);
    }

    #[Override]
    public function getTokens(): iterable
    {
        while (($token = $this->next()) !== null) {
            yield $token;
        }
    }
}
