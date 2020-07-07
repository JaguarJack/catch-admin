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
     * 存储信息
     *
     * @time 2020年07月07日
     * @return false|int
     */
    public function storeStatus()
    {
        return file_put_contents($this->getWorkerStatusPath(), $this->getWorkerStatus());
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

        return file_exists($this->getWorkerStatusPath()) ? file_get_contents($this->getWorkerStatusPath()) : '';
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
        $path = runtime_path('schedule' . DIRECTORY_SEPARATOR);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path . 'master.pid';
    }

    /**
     * 获取 worker 状态存储地址
     *
     * @time 2020年07月07日
     * @return string
     */
    protected function getWorkerStatusPath()
    {
        $path = runtime_path('schedule' . DIRECTORY_SEPARATOR);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path . 'worker-status.txt';
    }
}