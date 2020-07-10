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

trait Store
{
    /**
     * 存储 pid
     *
     * @time 2020年07月05日
     * @param $pid
     * @return false|int
     */
    public function storeMasterPid($pid)
    {
        $path = $this->getMasterPidPath();

        return file_put_contents($path, $pid);
    }

    /**
     * 清除退出的 worker 信息
     *
     * @time 2020年07月08日
     * @param $pid
     * @return void
     */
    protected function unsetWorkerStatus($pid)
    {
        $this->table->del($this->getColumnKey($pid));
    }


    /**
     * 输出
     *
     * @time 2020年07月07日
     * @return false|string
     */
    public function output()
    {
        // 等待信号输出
        sleep(1);

        return $this->getProcessStatusInfo();
    }

    /**
     * 获取 pid
     *
     * @time 2020年07月05日
     * @return int
     */
    public function getMasterPid()
    {
        $pid = file_get_contents($this->getMasterPidPath());

        return intval($pid);
    }

    /**
     * 获取配置地址
     *
     * @time 2020年07月05日
     * @return string
     */
    protected function getMasterPidPath()
    {
        return  config('catch.schedule.master_pid_file');
    }

    /**
     * 创建任务调度文件夹
     *
     * @time 2020年07月09日
     * @return string
     */
    protected function schedulePath()
    {
        $path = config('catch.schedule.store_path');

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }


    /**
     * 进程状态文件
     *
     * @time 2020年07月09日
     * @return string
     */
    protected function getSaveProcessStatusFile()
    {
        return $this->schedulePath() . '.worker-status';
    }

    /**
     *  保存进程状态
     *
     * @time 2020年07月09日
     * @return void
     */
    protected function saveProcessStatus()
    {
        file_put_contents($this->getSaveProcessStatusFile(), $this->renderProcessesStatusToString());
    }

    /**
     * 获取进程状态
     *
     * @time 2020年07月09日
     * @return false|string
     */
    protected function getProcessStatusInfo()
    {
        return file_get_contents($this->getSaveProcessStatusFile());
    }
}