<?php

namespace Modules\Permissions\Enums;

use Catch\Enums\Enum;

enum MenuType: int implements Enum
{
    case Top = 1; // 目录
    case Menu = 2; // 菜单
    case Action = 3; // 按钮

    public function value(): int
    {
        // TODO: Implement value() method.
        return match ($this) {
            self::Top => 1,
            self::Menu => 2,
            self::Action => 3,
        };
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return match ($this) {
            self::Top => '目录类型',
            self::Menu => '菜单类型',
            self::Action => '按钮类型',
        };
    }
}
