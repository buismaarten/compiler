<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

use Package\Lexer\Lexer;
use Package\Parser\Parser;

require_once __DIR__.'/vendor/autoload.php';


$lexer = new Lexer('a + 3 * 4');
$parser = new Parser($lexer);

echo json_encode($parser->parse(), JSON_PRETTY_PRINT);
echo "\n";
