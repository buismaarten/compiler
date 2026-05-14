<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

require_once __DIR__.'/src/Token/TokenType.php';
require_once __DIR__.'/src/Token/Token.php';
require_once __DIR__.'/src/Lexer/LexerInterface.php';
require_once __DIR__.'/src/Lexer/Lexer.php';
require_once __DIR__.'/src/Parser/ParserInterface.php';
require_once __DIR__.'/src/Parser/Parser.php';


$lexer = new Lexer("2 + 3 * 4");
$parser = new Parser($lexer);

echo $parser->parse();
exit;
