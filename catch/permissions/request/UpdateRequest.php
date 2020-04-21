<?php
namespace catchAdmin\permission\request;

use catchAdmin\user\model\Users;
use catcher\base\CatchRequest;

class UpdateRequest extends CatchRequest
{
    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'username|用户名' => 'require|max:20',
            'password|密码' => 'sometimes|min:5|max:12',
            'email|邮箱'    => 'require|email|unique:'.Users::class,
        ];
    }

    protected function message(): array
    {
        // TODO: Implement message() method.
    }
}
