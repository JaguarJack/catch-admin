<?php

namespace catchAdmin\login\validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'name|用户名'    => 'require|max:25',
        'password|密码'  => 'require',
        'captcha|验证码' => 'require|captcha'
    ];
}