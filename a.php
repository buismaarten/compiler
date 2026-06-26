<?php

$a = file_get_contents('tests/Tokenizer/TokenizerTest.php');
$tokens = PhpToken::tokenize($a);

foreach ($tokens as $token) {
    echo "Line {$token->line}, position {$token->pos}: {$token->getTokenName()} ('{$token->text}')", PHP_EOL;
}
