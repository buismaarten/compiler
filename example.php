<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

use Buismaarten\Parser\Lexer\Lexer;
use Buismaarten\Parser\Parser\Parser;

require_once __DIR__.'/vendor/autoload.php';


$lexer = new Lexer('2 + 3 * 4');
$parser = new Parser($lexer);

print_r($parser->parse());
echo "\n";
