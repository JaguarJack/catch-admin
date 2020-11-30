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
            'account|账号'    => 'require|unique:' . Users::class . ',account,' . $this->user()->id,
        ];
    }
}
