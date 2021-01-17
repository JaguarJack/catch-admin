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

declare (strict_types = 1);

namespace catchAdmin\monitor\command;

use catchAdmin\monitor\command\process\Master;
use catchAdmin\monitor\command\process\Process;
use catcher\facade\FileSystem;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\console\Table;

class CatchCrontabCommand extends Command
{
    protected $pid;

    protected function configure()
    {
        // 指令配置
        $this->setName('catch:crontab')
            ->addArgument('action', Argument::OPTIONAL, '[start|reload|stop|restart]', 'start')
            ->addOption('daemon', '-d', Option::VALUE_NONE, 'daemon mode')
            ->addOption('pid', '-p', Option::VALUE_REQUIRED, 'you can send signal to the process of pid')
            ->addOption('static', '-s', Option::VALUE_REQUIRED, 'default static process number', 1)
            ->addOption('dynamic', '-dy', Option::VALUE_REQUIRED, 'default dynamic process number', 10)
            ->addOption('interval', '-i', Option::VALUE_REQUIRED, 'interval/seconds', 5)
            ->setDescription('start catch crontab schedule');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$input->hasOption('pid')) {
            $this->pid = Master::getMasterPid();
        } else {
            $this->pid = $input->getOption('pid');
        }

        if (!extension_loaded('pcntl') || !extension_loaded('posix')) {
            $output->error('you should install extension [pcntl && posix]');
        } else {
            $this->{$input->getArgument('action')}();
        }
    }

    /**
     * 进程启动
     *
     * @time 2020年09月14日
     * @return void
     */
    protected function start()
    {
        $worker = new Master();

        $worker->staticNumber($this->input->getOption('static'))
            ->dynamic($this->input->getOption('dynamic'))
            ->interval($this->input->getOption('interval'))
            ->asDaemon($this->input->hasOption('daemon'))
            ->run();
    }

    /**
     * 停止任务
     *
     * @time 2020年07月07日
     * @return void
     */
    protected function stop()
    {
        $retryTimes = 0;

        if (Process::isAlive($this->pid)) {
            $this->output->info('🔨 catch crontab is running.');
            Process::kill($this->pid, SIGTERM);
            // 睡眠 1 秒
            $this->output->info('⌛️ killing catch crontab service, please waiting...');
            sleep(1);
            if (!Process::isAlive($this->pid)) {
                $this->output->info('🎉 catch crontab stopped!');
            } else {
                while (true) {
                    Process::kill($this->pid, SIGKILL);
                    if (Process::isAlive($this->pid)) {
                        $retryTimes++;
                        $this->output->info('🔥 retry $retryTimes times');
                        usleep(500 * 1000);
                        if ($retryTimes >= 3) {
                            $this->output->info('💔 catch crontab is running, stop failed, please use [kill -9 {$this->pid}] to Stop it');
                            break;
                        }
                    } else {
                        $this->output->info('🎉 catch crontab stopped!');
                        break;
                    }
                }
            }
            Master::exitMasterDo();
        } else {
            $this->output->error('🤔️ catch crontab is not running, Please Check it!');
        }

        $this->output->info('🎉 stop catch crontab successfully');
    }

    /**
     * 重启任务
     *
     * @time 2020年07月07日
     * @return void
     */
    protected function reload()
    {
        Process::kill($this->pid, SIGUSR1);
    }

    /**
     * 重启
     *
     * @time 2020年07月07日
     * @return void
     */
    protected function restart()
    {
        $this->stop();

        $this->output->info('catch crontab restarting...');

        usleep(500 * 1000);

        $this->start();
    }

    /**
     * status
     *
     * @time 2020年07月22日
     * @return void
     */
    protected function status()
    {
        if ($this->pid) {
            if (Process::isAlive($this->pid)) {
                Process::kill($this->pid, SIGUSR2);

                usleep(100000);

                $worker = new Master;
                $table = new Table;

                $table->setHeader(['PID', '名称', '内存', '处理任务', '开始时间', '运行时间', '状态'], Table::ALIGN_CENTER);

                $table->setRows($worker->getWorkerStatus(), Table::ALIGN_CENTER);

                $this->output->info($table->render());
            } else {
                $this->output->error('🤔️ catch crontab is not running, Please Check it!');
            }
        } else {
            $this->output->error('🤔️ catch crontab is not running, Please Check it!');
        }
    }
}
