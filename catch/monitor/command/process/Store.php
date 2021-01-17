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

use catcher\facade\FileSystem;

trait Store
{
    /**
     * worker 根目录
     *
     * @time 2020年07月23日
     * @return string
     */
    public static function storeTaskPath()
    {
        $path = config('catch.crontab.store_path');

        if (!Filesystem::exists($path)) {
            FileSystem::makeDirectory($path, 0777, true);
        }

        return $path;
    }

    /**
     * 保存信息备用
     *
     * @time 2020年07月29日
     * @return void
     */
    protected function saveTaskInfo()
    {
         FileSystem::put(self::storeTaskPath() . 'information.json', \json_encode([
             'name' => $this->name,
             'static' => $this->static,
             'dynamic' => $this->dynamic,
             'interval' => $this->interval,
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    /**
     * worker master pid
     *
     * @time 2020年07月23日
     * @return string
     */
    public static function masterPidStorePath()
    {
        return self::storeTaskPath() . 'master.pid';
    }

    /**
     * worker master status
     *
     * @time 2020年07月23日
     * @return string
     */
    public static function statusPath()
    {
        return self::storeTaskPath() . 'master.status';
    }


    /**
     * worker status
     *
     * @time 2020年07月23日
     * @param string $name
     * @return string
     */
    public static function workerStatusPath($name)
    {
        $path = self::storeTaskPath() . 'status/';

        if (!FileSystem::exists($path)) {
            FileSystem::makeDirectory($path, 0777, true);
        }

        return $path . $name . '.status';
    }

    /**
     *
     * @time 2020年07月23日
     * @return string
     */
    public static function getWorkerStatusPath()
    {
        return self::storeTaskPath() . 'status/';
    }

    /**
     * worker log
     *
     * @time 2020年07月23日
     * @return string
     */
    public static function stdoutPath()
    {
        return self::storeTaskPath() . 'errors.log';
    }


    /**
     * 获取 master pid
     *
     * @time 2020年07月21日
     * @return false|string
     */
    public static function getMasterPid()
    {
        $pidFile = config('catch.crontab.master_pid_file');

        if (!FileSystem::exists($pidFile)) {
            return 0;
        }

        return FileSystem::sharedGet($pidFile);
    }

    /**
     * status
     *
     * @time 2020年07月21日
     * @return false|string
     */
    public function renderStatus()
    {
        return FileSystem::sharedGet(self::statusPath());
    }

    /**
     * 运行时间
     *
     * @time 2020年07月23日
     * @param $runtime
     * @return string
     */
    protected function getRunningTime($runtime)
    {
        $day = 3600 * 24;
        if ($runtime > $day) {
            $days = floor($runtime / $day);
            return $days . '天:' . gmstrftime('%H:%M:%S', $runtime % $day);
        } else {
            return gmstrftime('%H:%M:%S', $runtime);
        }
    }

    /**
     * 获取工作进程
     *
     * @time 2020年07月23日
     * @return mixed
     */
    public function getWorkerStatus()
    {
        usleep(500 * 1000);

        $files = FileSystem::glob(self::storeTaskPath() . 'status/*.status');

        $workerStatus = [];

        foreach ($files as $file) {
            $workerStatus[] = explode("\t", FileSystem::sharedGet($file));
        }

        return $workerStatus;
    }

    /**
     * 设置进程状态
     *
     * @time 2020年07月23日
     * @param $name
     * @param int $dealNum
     * @param string $status
     * @return void
     */
    protected function setWorkerStatus($name, $dealNum = 0, $status = 'running')
    {
        $startAt = strpos($name, 'worker') ? $this->worker_start_at : $this->start_at;

        if ($this->daemon) {
            FileSystem::put($this->workerStatusPath($this->workerStatusFileName($name)), implode("\t", [
                posix_getpid(),
                $name,
                floor(memory_get_usage() / 1024 / 1024) . 'M',
                $dealNum,
                date('Y-m-d H:i:s', $startAt),
                $this->getRunningTime(time() - $startAt),
                $status
            ]));
        }
    }

    /**
     * 进程名称
     *
     * @time 2020年09月15日
     * @param $name
     * @return string
     */
    protected function workerStatusFileName($name)
    {
        return $name . '_' . posix_getpid();
    }

    /**
     * 删除进程状态文件
     *
     * @time 2020年09月15日
     * @param $pid
     * @return void
     */
    protected function deleteWorkerStatusFile($pid)
    {
        @unlink(self::workerStatusPath($this->name . ' worker_' . $pid));
    }

    /**
     * 退出
     *
     * @time 2020年09月15日
     * @return void
     */
    public static function exitMasterDo()
    {
        @unlink(self::masterPidStorePath());
        @unlink(self::statusPath());
        Filesystem::deleteDirectory(self::getWorkerStatusPath());
    }
}