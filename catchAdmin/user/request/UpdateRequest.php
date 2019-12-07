<?php
namespace catchAdmin\user\request;

use catchAdmin\user\model\Users;
use catcher\base\BaseRequest;

class UpdateRequest extends BaseRequest
{
    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'username|用户名' => 'require|max:20',
            'password|密码' => 'sometimes|min:5|max:12',
            'passwordConfirm|密码' => 'sometimes|confirm:password',
            'email|邮箱'    => 'require|email|unique:'.Users::class,
        ];
    }

    protected function message(): array
    {
        // TODO: Implement message() method.
    }
}
