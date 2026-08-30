<?php

namespace Modules\System\Enums;

use Catch\Enums\Enum;

enum SmsChannel: string implements Enum
{
    case ALIYUN = 'aliyun'; // 阿里云
    case QCLOUD = 'qcloud'; // 腾讯云

    public function value(): string
    {
        // TODO: Implement value() method.
        return match ($this) {
            self::ALIYUN => 'aliyun',
            self::QCLOUD => 'qcloud',
        };
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return match ($this) {
            self::ALIYUN => '阿里云',
            self::QCLOUD => '腾讯云',
        };
    }

    public function assert(string $value): bool
    {
        return $this->value === $value;
    }
}
