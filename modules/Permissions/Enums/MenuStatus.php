<?php

namespace Modules\Permissions\Enums;

use Catch\Enums\Enum;

enum MenuStatus: int implements Enum
{
    case Show = 1; // 显示
    case Hidden = 2; // 隐藏

    public function value(): int
    {
        // TODO: Implement value() method.
        return match ($this) {
            self::Show => 1,
            self::Hidden => 2,
        };
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return match ($this) {
            self::Show => '显示',
            self::Hidden => '隐藏',
        };
    }
}
