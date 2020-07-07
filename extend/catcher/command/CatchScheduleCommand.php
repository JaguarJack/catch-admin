<?php

declare (strict_types = 1);

namespace catcher\command;

use catcher\library\crontab\ManageProcess;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class CatchScheduleCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('catch:schedule')
            ->addArgument('option', Argument::OPTIONAL, '[start|reload|stop|restart||status]', 'start')
            ->setDescription('start task schedule');
    }

    protected function execute(Input $input, Output $output)
    {
        $process = new ManageProcess();
        $option = $input->getArgument('option');
        $this->{$option}($process);
    }
    
    /**
     * 进程启动
     *
     * @time 2020年07月07日
     * @param ManageProcess $process
     * @return void
     */
    protected function start(ManageProcess $process)
    {
        $process->start();
    }

    /**
     * 状态输出
     *
     * @time 2020年07月07日
     * @param ManageProcess $process
     * @return void
     */
    protected function status(ManageProcess $process)
    {
        $process->status();

        $this->output->info($process->output());
    }

    /**
     * 停止任务
     *
     * @time 2020年07月07日
     * @param ManageProcess $process
     * @return void
     */
    protected function stop(ManageProcess $process)
    {
        $process->stop();
    }

    /**
     * 重启任务
     *
     * @time 2020年07月07日
     * @param ManageProcess $process
     * @return void
     */
    protected function reload(ManageProcess $process)
    {
        $process->reload();
    }

    /**
     * 重启
     *
     * @time 2020年07月07日
     * @param ManageProcess $process
     * @return void
     */
    protected function restart(ManageProcess $process)
    {
        $process->stop();

        $process->start();
    }
}
