<?php
namespace catchAdmin\monitor\tables\forms;

use catcher\library\form\Form;

class Crontab extends Form
{
    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            self::input('name', '任务名称')->required(),

            self::radio('group', '分组', 1)->options(
                self::options()->add('默认', 1)->add('系统', 2)->render()
            ),

            self::input('task', 'command指令')->required(),

            self::input('cron', 'cron表达式')->required(),

            self::radio('tactics', '执行策略', 1)->options(
                self::options()
                    ->add('立即执行', 1)
                    ->add('执行一次', 2)
                    ->add('正常执行', 3)
                    ->render()
            ),

            self::radio('status', '状态', 1)->options(
                self::options()->add('启用', 1)->add('禁用', 2)->render()
            ),

            self::textarea('remark', '备注')
        ];
    }
}