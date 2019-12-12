<?php
namespace catchAdmin\login\request;

use catcher\base\CatchRequest;

class LoginRequest extends CatchRequest
{
    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'name|用户名'    => 'require|max:25',
            'password|密码'  => 'require',
           // 'captcha|验证码' => 'require|captcha'
        ];
    }

    protected function message(): array
    {
        // TODO: Implement message() method.
        return [];
    }
}
