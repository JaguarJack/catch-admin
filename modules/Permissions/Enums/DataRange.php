<?php

namespace Modules\Permissions\Enums;

use Catch\Enums\Enum;

enum DataRange: int implements Enum
{
    case All_Data = 1; // 全部数据
    case Personal_Choose = 2; // 自定义数据
    case Personal_Data = 3; // 本人数据
    case Department_Data = 4; // 部门数据
    case Department_DOWN_Data = 5; // 部门及以下数据


    public function value(): int
    {
        // TODO: Implement value() method.
        return match ($this) {
            self::All_Data => 1,
            self::Personal_Choose => 2,
            self::Personal_Data => 3,
            self::Department_Data => 4,
            self::Department_DOWN_Data => 5,
        };
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return match ($this) {
            self::All_Data => '全部数据',
            self::Personal_Choose => '自定义数据',
            self::Personal_Data => '本人数据',
            self::Department_Data => '部门数据',
            self::Department_DOWN_Data => '部门及以下数据',
        };
    }

    /**
     * assert value
     *
     * @param int $value
     * @return bool
     */
    public function assert(int $value): bool
    {
       return $this->value === $value;
    }
}
