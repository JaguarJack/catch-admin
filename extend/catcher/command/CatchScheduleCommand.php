<?php

declare (strict_types = 1);

namespace catcher\command;

use catcher\library\crontab\Master;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class CatchScheduleCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('catch:schedule')
            ->addArgument('option', Argument::OPTIONAL, '[start|reload|stop|restart||status]', 'start')
            ->addOption('daemon', '-d', Option::VALUE_NONE, 'daemon mode')
            ->setDescription('start task schedule');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!extension_loaded('swoole')) {
            $output->error('Swoole Extension Not Installed! You can use [pecl] to install swoole');
        } else {
            $master = new Master();
            if ($this->input->hasOption('daemon')) {
                $master->daemon();
            }
            $this->{$input->getArgument('option')}($master);
        }
    }
    
    /**
     * 进程启动
     *
     * @time 2020年07月07日
     * @param Master $process
     * @return void
     */
    protected function start(Master $process)
    {
        $process->start();
        $this->output->info($process->renderProcessesStatusToString());
    }

    /**
     * 状态输出
     *
     * @time 2020年07月07日
     * @param Master $process
     * @return void
     */
    protected function status(Master $process)
    {
        $process->status();

        $this->output->info($process->output());
    }

    /**
     * 停止任务
     *
     * @time 2020年07月07日
     * @param Master $process
     * @return void
     */
    protected function stop(Master $process)
    {
        $process->stop();

        $this->output->info('stop catch schedule successfully');
    }

    /**
     * 重启任务
     *
     * @time 2020年07月07日
     * @param Master $process
     * @return void
     */
    protected function reload(Master $process)
    {
        $process->reload();
    }

    /**
     * 重启
     *
     * @time 2020年07月07日
     * @param Master $process
     * @return void
     */
    protected function restart(Master $process)
    {
        $process->stop();

        $process->start();
    }
}
