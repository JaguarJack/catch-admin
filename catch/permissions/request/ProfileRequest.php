<?php
namespace catchAdmin\permissions\request;

use catchAdmin\permissions\model\Users;
use catcher\base\CatchRequest;

class ProfileRequest extends CatchRequest
{
    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'username|用户名' => 'require|max:20',
            'email|邮箱'    => 'require|email|unique:'.Users::class . ',email,' . $this->user()->id,
        ];
    }
}
