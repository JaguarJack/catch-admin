<?php
namespace catchAdmin\permissions\tables\forms;

use catcher\library\form\Form;

class Job extends Form
{
    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            self::input('job_name', '岗位名称')->required(),

            self::input('coding', '岗位编码'),

            self::radio('status', '状态')->value(1)->options(
                self::options()->add('启用', 1)->add('禁用', 2)->render()
            ),

            self::number('sort', '排序')->value(1)->min(1)->max(10000),
        ];
    }
}