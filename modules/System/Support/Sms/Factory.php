<?php

namespace Modules\System\Support\Sms;

use Catch\Exceptions\FailedException;

class Factory
{
    public static function make(): Sms
    {
        $channels = [
            'aliyun' => AliYun::class,
            'qcloud' => QCloud::class,
        ];

        if (! config('sms.default')) {
            throw new FailedException('短信通道还未配置，请先进行短信通道配置');
        }

        return app($channels[config('sms.default')]);
    }
}
