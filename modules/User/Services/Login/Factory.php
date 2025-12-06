<?php

namespace Modules\User\Services\Login;

use Catch\Exceptions\FailedException;

class Factory
{
    public static function make(array $params): LoginInterface
    {
        $driver = (new static())->getLoginDriver($params);

        return new (__NAMESPACE__.'\\'.ucfirst($driver));
    }

    protected function getLoginDriver($params): string
    {
        if ($params['account'] ?? false) {
            return 'password';
        }

        if (isset($params['mobile']) && isset($params['sms_code'])) {
            return 'sms';
        }

        if ($params['wx_code'] ?? false) {
            return 'wechat';
        }

        throw new FailedException('暂不支持该登陆方式');
    }
}
