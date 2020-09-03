<?php
namespace catchAdmin\system\request\sensitiveWord;

use catchAdmin\system\model\SensitiveWord;
use catcher\base\CatchRequest;

class CreateRequest extends CatchRequest
{
    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'word|词汇' => 'unique:'.SensitiveWord::class.',word',
        ];
    }
}
