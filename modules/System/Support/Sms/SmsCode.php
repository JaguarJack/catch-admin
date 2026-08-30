<?php

namespace Modules\System\Support\Sms;

use Catch\Exceptions\FailedException;
use Modules\System\Models\SystemSmsCode;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsCode
{
    protected Sms $channel;

    public const LOGIN_BEHAVIOR = 'login';

    public const REGISTER_BEHAVIOR = 'register';

    public function __construct()
    {
        $this->channel = Factory::make();
    }

    /**
     * @throws \Throwable
     */
    public function login(string $mobile): bool
    {
        try {
            $smsCodeModel = new SystemSmsCode();

            if ($smsCodeModel->hasCode($mobile, self::LOGIN_BEHAVIOR)) {
                throw new FailedException('验证码未使用或者未过期');
            }

            $code = $this->getCode();
            $this->channel->send(self::LOGIN_BEHAVIOR, $mobile, [$code]);
            $smsCodeModel->store($mobile, self::LOGIN_BEHAVIOR, $code);

            return true;
        } catch (NoGatewayAvailableException $exception) {
            throw new FailedException($exception->getLastException()->getMessage());
        } catch (\Throwable|\Exception $e) {
            throw new FailedException($e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function register(string $mobile)
    {
        $smsCodeModel = new SystemSmsCode();

        $smsCodeModel->hasCode($mobile, 'register');
    }

    protected function getCode(): int
    {
        return rand(100000, 999999);
    }
}
