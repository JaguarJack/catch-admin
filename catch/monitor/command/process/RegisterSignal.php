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

namespace catchAdmin\monitor\command\process;

use catchAdmin\monitor\model\Crontab;
use catcher\facade\FileSystem;
use Cron\CronExpression;
use EasyWeChat\Kernel\Messages\News;
use think\cache\driver\Redis;

trait RegisterSignal
{
    public function registerSignal()
    {
        // 退出信号
        $this->exit();
        // 等待子进程退出
        $this->waitWorkersExit();
        // 动态扩容
        $this->workerChecked();
        // 重启进程
        $this->reload();
        // 统计信息
        $this->showStatus();
    }

    /**
     * 进程退出
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function exit()
    {
        Process::signal(SIGTERM, function ($signal) {
            foreach ($this->workerIds as $pid => $v) {
                if (isset($this->workerIds[$pid])) {
                    unset($this->workerIds[$pid]);
                }
                Process::kill($pid, SIGTERM);
            }
            Process::kill(self::getMasterPid(), SIGKILL);
        });

        Process::signal(SIGINT, function ($signal) {
            foreach ($this->workerIds as $pid => $v) {
                if (isset($this->workerIds[$pid])) {
                    unset($this->workerIds[$pid]);
                }
                Process::kill($pid, SIGKILL);
            }
            Process::kill(self::getMasterPid(), SIGKILL);
        });
    }

    /**
     * 子进程退出
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function waitWorkersExit()
    {
        Process::signal(SIGCHLD, function ($signal) {

        });
    }


    /**
     * 进程检测
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function workerChecked()
    {
        Process::signal(SIGALRM, function ($signal) {
            $process = new Process(function (Process $process) {
                $crontabs = Crontab::where('status', Crontab::ENABLE)
                            ->where('tactics', '<>', Crontab::EXECUTE_FORBIDDEN)
                            ->select()->toArray();

                // 任务
                foreach ($crontabs as $crontab) {
                    $can = date('Y-m-d H:i', CronExpression::factory(trim($crontab['cron']))
                                ->getNextRunDate(date('Y-m-d H:i:s'), 0 , true)
                                ->getTimestamp()) == date('Y-m-d H:i', time());

                    if ($can) {
                        // 如果任务只执行一次 之后禁用该任务
                        if ($crontab['tactics'] === Crontab::EXECUTE_ONCE) {
                            Crontab::where('id', $crontab['id'])->update([
                                'status' => Crontab::DISABLE,
                            ]);
                        }

                        $redis = $this->getRedisHandle();

                        $redis->lpush($this->crontabQueueName, json_encode([
                            'id' => $crontab['id'],
                            'task' => $crontab['task'],
                        ]));
                    }
                }

                $process->exit();
            });

            $process->start();

            Process::alarm($this->interval);
        });
    }

    /**
     * 重启
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function reload()
    {
        Process::signal(SIGUSR1, function ($signal) {
            $this->worker_start_at = 0;
            foreach ($this->workerIds as $pid => $v) {
                Process::kill($pid, SIGTERM);
            }
        }, false);
    }

    /**
     * 预留信号
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function showStatus()
    {
        Process::signal(SIGUSR2, function ($signal) {
            $this->setWorkerStatus($this->name . ' master');
            foreach ($this->workerIds as $pid => $v) {
                Process::kill($pid, SIGUSR2);
            }
        });
    }
}


