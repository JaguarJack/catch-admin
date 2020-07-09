<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library\crontab;

use Swoole\Process;
use catcher\library\crontab\Process as MProcess;
use Swoole\Timer;

class ManageProcess
{
    use RegisterSignal, MProcess, Store;

    /**
     * 动态扩展的最大 process 数量
     *
     * @var int
     */
    protected $maxNum = 10;

    /**
     * 常驻 process
     *
     * @var int
     */
    protected $staticNum = 1;

    /**
     * 存储 process 信息
     *
     * @var array
     */
    protected $processes = [];

    /**
     * 主进程ID
     *
     * @var
     */
    protected $master_pid;

    /**
     * pid 文件名称
     *
     * @var string
     */
    protected $mater = 'catch-master';

    /**
     * process status 存储文件
     *
     * @var string
     */
    protected $processStatus = 'process-status';

    // 版本
    const VERSION = '1.0.0';

    // process 等待状态
    const WAITING = 'waiting';
    // process 繁忙状态
    const BUSYING = 'busying';

    /**
     * 启动进程
     *
     * @time 2020年07月07日
     * @return void
     */
    public function start()
    {
        // 守护进程
       // Process::daemon(true, false);
        // alarm 信号
        // Process::alarm(1000 * 1000);
        // 1s 调度一次
        $this->timeTick(1000, $this->schedule());
        // 注册信号
        $this->registerSignal();
        // 存储 pid
        $this->storeMasterPid(getmypid());
        // pid
        $this->master_pid = getmypid();
        // 初始化进程
        $this->initProcesses();
    }

    /**
     * 自定义 tick 关闭协程
     *
     * @time 2020年07月08日
     * @param int $time
     * @param $callable
     * @return void
     */
    protected function timeTick(int $time, $callable)
    {
        // 关闭协程
        Timer::set([
            'enable_coroutine' => false,
        ]);

        Timer::tick($time, $callable);
    }

    /**
     * 调度
     *
     * @time 2020年07月07日
     * @return \Closure
     */
    protected function schedule()
    {
        return function () {
            $schedule = new Schedule();
            $schedule->command('catch:cache')->everyThirtySeconds();

            foreach ($schedule->getCronTask() as $cron) {
                if ($cron->can()) {
                    list($waiting, $process) = $this->hasWaitingProcess();
                    if ($waiting) {
                        // 向 process 投递 cron
                       // var_dump(serialize($cron));
                       //$process->push(serialize($cron));
                        var_dump($process->pop());
                    } else {
                        // 创建临时 process 处理，处理完自动销毁
                        $this->createProcess($cron);
                    }
                }
            }
        };
    }

    /**
     * Create Task
     *
     * @time 2019年08月06日
     * @param Cron $cron
     * @return void
     */
    protected function createProcess(Cron $cron)
    {
        $process = new Process(function (Process $process) use($cron) {
            echo 'hello world';
            //$cron->run();
            $process->exit();
        });

        // $process->name(sprintf('worker: '));

        $process->start();
    }

    /**
     * 创建静态 worker 进程
     *
     * @time 2020年07月05日
     * @return Process
     */
    protected function createStaticProcess()
    {
        $process =  new Process($this->createProcessCallback());

        // 使用非阻塞队列
        $process->useQueue(1, 2|Process::IPC_NOWAIT);

        return $process;
    }

    /**
     * 初始化 workers
     *
     * @time 2020年07月03日
     * @return void
     */
    protected function initProcesses()
    {
        for ($i = 0; $i < $this->staticNum; $i++) {

            $process = $this->createStaticProcess();
            // $worker->name("[$i+1]catch-worker");

            $process->start();

            $this->processes[$process->pid] = $process;

            $this->storeStatus($this->processInfo($process));
        }
    }

    /**
     * 记录日志
     *
     * @time 2020年07月07日
     * @return void
     */
    protected function log()
    {
        fwrite(STDOUT, runtime_path('schedule') . 'error.log');
    }
}