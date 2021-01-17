<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace catchAdmin\monitor\command;

use catchAdmin\monitor\command\process\Process;
use catchAdmin\monitor\model\Crontab;
use Cron\CronExpression;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class ScheduleCommand extends Command
{
    protected $pid;

    protected function configure()
    {
        // 指令配置
        $this->setName('catch:schedule')
            ->setDescription('catch schedule');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            foreach ($this->getExecuteAbleCommands() as $command) {

                $process = new Process(function (Process $process) use ($command) {
                        $this->getConsole()->call($command);
                        $process->exit();
                });

                $process->start();
            }
        } catch (\Exception $e) {
            Log::error('CatchSchedule Error:' . $e->getMessage());
        }

    }

    /**
     * 获取可执行任务
     *
     * @time 2021年01月17日
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    protected function getExecuteAbleCommands()
    {
        $executeAbleCommands = [];

        Crontab::where('status', Crontab::ENABLE)
            ->where('tactics', '<>', Crontab::EXECUTE_FORBIDDEN)
            ->select()
            ->each(function ($command) use (&$executeAbleCommands){
                if ($command->tactics == Crontab::EXECUTE_IMMEDIATELY) {
                    $executeAbleCommands[] = $command->task;
                    return true;
                }

                $can = date('Y-m-d H:i', CronExpression::factory(trim($command->cron))
                        ->getNextRunDate(date('Y-m-d H:i:s'), 0, true)
                        ->getTimestamp()) == date('Y-m-d H:i', time());

                if ($can) {
                    // 如果任务只执行一次 之后禁用该任务
                    if ($command->tactics === Crontab::EXECUTE_ONCE) {
                        Crontab::where('id', $command->id)->update([
                            'status' => Crontab::DISABLE,
                        ]);
                    }

                    $executeAbleCommands[] = $command->task;
                }
                return true;
            });

        return $executeAbleCommands;
    }
}
