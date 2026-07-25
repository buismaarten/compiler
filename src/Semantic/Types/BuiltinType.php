<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Semantic\Types;

use Package\Language\BuiltinTypeKind;

final class BuiltinType extends AbstractType
{
    public BuiltinTypeKind $kind;

    public function __construct(BuiltinTypeKind $kind)
    {
        $this->kind = $kind;
    }
}
