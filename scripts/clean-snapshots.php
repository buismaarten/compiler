<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

if (!getenv('UPDATE_SNAPSHOTS') || getenv('UPDATE_SNAPSHOTS') === 'false') {
    return;
}

foreach (glob('./tests/{*,*/*}/__snapshots__', GLOB_BRACE) as $path) {
    shell_exec("rm -rf {$path}");
}
