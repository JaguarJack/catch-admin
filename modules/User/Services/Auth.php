<?php

namespace Modules\User\Services;

use Catch\Exceptions\FailedException;
use Illuminate\Support\Facades\Event;
use Modules\User\Events\Login;
use Modules\User\Services\Login\Factory;

class Auth
{
    public function attempt(array $params): array
    {
        try {
            $auth = Factory::make($params);

            $user = $auth->auth($params);

            // 用户是否禁用
            if ($user->isDisable()) {
                throw new FailedException('⚠该账户已被禁用');
            }

            $token = $user->createToken('token', expiresAt: config('sanctum.expiration') ? now()->addMinutes(config('sanctum.expiration')) : null)
                          ->plainTextToken;

            // 登录成功事件
            Event::dispatch(new Login($user, $token));

            return compact('token');
        } catch (\Exception|\Throwable $e) {
            // 登录失败日志
            Event::dispatch(new Login(null));
            throw new FailedException($e->getMessage());
        }
    }
}
