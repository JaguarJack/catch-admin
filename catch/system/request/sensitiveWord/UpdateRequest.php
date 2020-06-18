<?php
namespace catchAdmin\system\request\sensitiveWord;

use catchAdmin\system\model\SensitiveWord;
use catcher\base\CatchRequest;

class UpdateRequest extends CatchRequest
{
    protected function rules(): array
    {
        $id = request()->route()['id'];
        // TODO: Implement rules() method.
        return [
            'word|词汇' => 'sensitive_word|unique:'.SensitiveWord::class.',word,'.$id,
        ];
    }

}