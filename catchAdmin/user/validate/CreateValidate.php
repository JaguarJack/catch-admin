<?php
namespace catchAdmin\user\validate;

use catcher\base\BaseValidate;

class CreateValidate extends BaseValidate
{
    protected function getRules(): array
    {
        // TODO: Implement getRules() method.
        return [
            'username|用户名' => 'require|max:20',
            'password|密码' => 'require|max:20',
            'email|邮箱'    => 'require|email'
        ];
    }
}
