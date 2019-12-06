<?php
namespace catchAdmin\user\validate;

use catchAdmin\user\model\Users;
use catcher\base\BaseValidate;

class UpdateValidate extends BaseValidate
{
    protected function getRules(): array
    {
        // TODO: Implement getRules() method.
        return [
            'username|用户名' => 'require|max:20',
            'password|密码' => 'require|max:20',
            'email|邮箱'    => 'require|email|unique:'.Users::class.',email,'.request()->route('user').',id',
        ];
    }
}
