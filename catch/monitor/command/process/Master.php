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

use Exception;
use catcher\facade\Filesystem;
use think\cache\driver\Redis;
use think\facade\Cache;

/**
 *
 * - 以后台形式工作 daemon
 * - 分静态进程和动态扩容进程
 * - 信号处理机制
 *   - 重启工作进程
 *   - 重启服务
 *   - 定时器 扩容工作进程
 *   - 关闭进程
 *   - 统计信息
 * - 是否拉起进程，工作进程销毁后是否继续主进程保留
 * - Fatal Error 处理
 * - Exception 处理
 * - 发生内存泄漏如何处理
 * - 错误输出到哪里
 * - 提供基础面板查看
 * - Log 文件的记录
 *
 * @time 2020年07月29日
 */
class Master
{
    use RegisterSignal, Attributes, Store, ParseTask;

    /**
     * 保存子进程 PID
     *
     * @var array
     */
    protected $workerIds = [];

    /**
     * 开始时间
     *
     * @var
     */
    public $start_at;

    /**
     * @var
     */
    public $worker_start_at;

    /**
     * 保存当前工作进程的数量
     *
     * @var
     */
    protected $allWorkersCount;

    /**
     * 保存当前重定向输出文件
     *
     * @var string
     */
    protected static $stdout;

    /**
     * 任务对象
     *
     * @var
     */
    protected $taskService;

    /**
     * 处理的任务数量
     *
     * @var int
     */
    protected $dealNum = 0;

    /**
     * busy waiting
     *
     * @var
     */
    protected $status = 'waiting';

    /**
     * @var
     */
    protected $redis;

    /**
     * 启动
     *
     * @time 2020年07月21日
     * @return void
     */
    public function run()
    {
        try {
            if ($this->daemon) {
                Process::daemon();
            }

            if ($this->interval) {
                Process::alarm($this->interval);
            }
            $this->init();
            // 初始化进程池
            $this->initWorkers();
            // 设置进程名称
            Process::setWorkerName($this->name . ' master');
            // 注册信号
            $this->registerSignal();
            // 写入进程状态
            $this->setWorkerStatus($this->name . ' master');
            // 信号发送
            while (true) {
                Process::dispatch();
                $pid = pcntl_waitpid(-1, $status, WNOHANG);
                Process::dispatch();
                if ($pid > 0) {
                    if (isset($this->workerIds[$pid])) {
                        unset($this->workerIds[$pid]);
                        $this->deleteWorkerStatusFile($pid);
                        $this->worker_start_at = time();
                        // 如果进程挂掉，正常退出码都是 0，当然这里可以自己定义，看 exit($status) 设置什么
                        // 真实的 exit code  pcntl_wexitstatus 函数获取
                        // exit code > 0 都是由于异常导致的
                        $exitCode = pcntl_wexitstatus($status);
                        if (!in_array($exitCode, [255, 250])) {
                            $this->forkStatic();
                        }
                    }
                    // 如果静态工作进程全部退出，会发生 CPU 空转，所以这里需要 sleep 1
                    if (!count($this->workerIds)) {
                        // sleep(1);
                        self::exitMasterDo();
                        exit(0);
                    }
                }

                usleep(500 * 1000);
            }
        } catch (\Throwable $exception) {
            // todo
            echo sprintf('[%s]: ', date('Y-m-d H:i:s')) . $exception->getMessage();
        }
    }

    /**
     * 初始化
     * @throws Exception
     */
    protected function init()
    {
        $this->displayErrors();
        $this->start_at = $this->worker_start_at = time();
        // 记录 masterID
        FileSystem::put(self::masterPidStorePath(), posix_getpid());
        // 保存信息
        $this->saveTaskInfo();
        // 初始化进程数量
        $this->allWorkersCount = $this->static;
        // 显示UI
        $this->display();
        // 重定向
        $this->dup();
    }

    /**
     * 初始化进程池
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function initWorkers()
    {
        $this->redis = $this->getRedisHandle();

        for ($i = 0; $i < $this->static; $i++) {
            $this->forkStatic();
        }
    }

    /**
     * fork 进程
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function forkDynamic()
    {
        $process = new Process(function (Process $process) {
            $redis = $this->getRedisHandle();
            while($crontab = $redis->rpop($this->crontabQueueName)) {
                $task = $this->getTaskObject(\json_decode($crontab, true));
                $task->run();
            }

            $process->exit();
        });

        $process->start();

        $this->workerIds[$process->pid] = true;
    }

    /**
     * 静态进程
     *
     * @time 2020年07月21日
     * @return void
     */
    protected function forkStatic()
    {
        $process = new Process(function (Process $process) {
            $process->initMemory();

            $name = $this->name . ' worker';
            $this->setWorkerStatus($name, $this->dealNum, $this->status);

            Process::setWorkerName($name);

            Process::signal(SIGUSR2, function ($signal) use ($name) {
                $this->setWorkerStatus($name, $this->dealNum, $this->status);
            });

            // 该信号保证进程完成任务后安全退出
            Process::signal(SIGTERM, function ($signal) {
                $this->exitSafely = true;
            });

            while (true) {
                /************** 任务 *********************/
                $this->status = 'busying';
                $this->setWorkerStatus($name, $this->dealNum, 'busying');

                // 处理定时任务
                while ($crontab = $this->redis->rpop($this->crontabQueueName)) {
                    $task = $this->getTaskObject(\json_decode($crontab, true));
                    $task->run();
                    if ($task->shouldExit()) {
                        $process->exit(250);
                    }
                    $this->dealNum += 1;
                }

                $this->status = 'waiting';
                $this->setWorkerStatus($name, $this->dealNum, 'waiting');
                /****************处理*********************/
                // 暂停一秒 让出CPU调度
                sleep(1);
                // 信号调度
                Process::dispatch();
                // 是否需要安全退出 || 查看内存是否溢出
                if ($this->exitSafely || $process->isMemoryOverflow()) {
                    $process->exit();
                    //exit(0);
                }
            }
        });

        $process->start();

        $this->workerIds[$process->pid] = true;
    }

    /**
     * 重定向文件流
     *
     * @time 2020年07月22日
     * @return void
     * @throws Exception
     */
    protected function dup()
    {
        if (!$this->daemon) {
            return;
        }

        global $stdout, $stderr;

        // 关闭标准输入输出
        fclose(STDOUT);
        fclose(STDIN);
        fclose(STDERR);

        // 重定向输出&错误
        $stdoutPath = self::$stdout ?: self::stdoutPath();

        !file_exists($stdoutPath) && touch($stdoutPath);
        // 等待 100 毫秒
        usleep(100 * 1000);

        $stdout = fopen($stdoutPath, 'a');

        $stderr = fopen($stdoutPath, 'a');

        return;
    }

    /**
     * 输出
     *
     * @time 2020年07月21日
     * @return string
     */
    public function output()
    {
        $isShowCtrlC = $this->daemon ? '' : 'Ctrl+c to stop' . "\r\n";

        $info = <<<EOT
 ---------------------------------------------------------------- 🚀                                                
|            _       _                            _        _     |
|           | |     | |                          | |      | |    |
|   ___ __ _| |_ ___| |__     ___ _ __ ___  _ __ | |_ __ _| |__  |
|  / __/ _` | __/ __| '_ \   / __| '__/ _ \| '_ \| __/ _` | '_ \ |
| | (_| (_| | || (__| | | | | (__| | | (_) | | | | || (_| | |_) ||
|  \___\__,_|\__\___|_| |_|  \___|_|  \___/|_| |_|\__\__,_|_.__/ |
|                                                                |                                                            |
|----------------------------------------------------------------|
$isShowCtrlC          
EOT;
        return file_put_contents(self::statusPath(), $info);
    }

    /**
     * 显示
     *
     * @time 2020年07月22日
     * @return false|int
     */
    protected function display()
    {
        $this->output();

        return fwrite(STDOUT, $this->renderStatus());
    }

    /**
     * 获取 redis 句柄
     *
     * @time 2020年09月15日
     * @return object|null
     */
    protected function getRedisHandle()
    {
        return Cache::store('redis')->handler();
    }
}