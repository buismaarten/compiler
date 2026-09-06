<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

if (!getenv('UPDATE_SNAPSHOTS')) {
    return;
}

// shell_exec('rm -rf ./tests/Parser/__snapshots__');
// shell_exec('rm -rf ./tests/Semantic/SymbolResolver/__snapshots__');
// shell_exec('rm -rf ./tests/Semantic/TypeResolver/__snapshots__');
