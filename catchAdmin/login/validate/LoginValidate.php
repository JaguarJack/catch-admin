<?php

namespace catchAdmin\login\validate;

use catcher\base\BaseValidate;

class LoginValidate extends BaseValidate
{
    protected function getRules(): array
    {
        // TODO: Implement getRules() method.
        return [
            'name|用户名'    => 'require|max:25',
            'password|密码'  => 'require',
            'captcha|验证码' => 'require|captcha'
        ];
    }
}