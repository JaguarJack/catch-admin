<?php

namespace Modules\User\Services\Login;

use Catch\Exceptions\FailedException;
use Illuminate\Support\Facades\Validator;
use Modules\System\Models\SystemSmsCode;
use Modules\User\Models\User;

class Sms implements LoginInterface
{
    /**
     * @param  array{mobile:string, sms_code: string}  $params
     */
    public function auth(array $params): User
    {
        // TODO: Implement auth() method.
        $this->valid($params);

        return User::where('mobile', $params['mobile'])->firstOrCreate(
            ['mobile' => $params['mobile']],
            ['username' => $params['mobile'], 'creator_id' => 0]
        );
    }

    protected function valid(array $params): void
    {
        $validator = Validator::make($params, [
            'mobile' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! preg_match('/^1\d{10}$/', $value)) {
                        $fail('手机格式不正确');
                    }
                },
            ],
            'sms_code' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($params) {
                    $smsCodeModel = new SystemSmsCode();
                    if (! $smsCodeModel->validCode($params['mobile'], 'login', $value)) {
                        $fail('验证码错误');
                    }
                },
            ],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            throw new FailedException($validator->errors()->first());
        }
    }
}
