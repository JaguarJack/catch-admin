<?php
namespace catchAdmin\system\tables\forms;

use catchAdmin\permissions\model\Department as DepartmentModel;
use catcher\library\form\Form;

class SensitiveWord extends Form
{
    public function fields(): array
    {
        return [
            Form::input('word', '敏感词')->required()->placeholder('请输入敏感词'),
        ];
    }
}