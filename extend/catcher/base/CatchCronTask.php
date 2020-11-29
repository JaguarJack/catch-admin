<?php
declare(strict_types=1);

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\base;

use catchAdmin\monitor\model\CrontabLog;

abstract class CatchCronTask
{
    protected $exceptionHappenTimes = 0;

    protected $exitTimes = 1;

    protected $crontab;

    /**
     * @time 2020年07月29日
     * @return mixed
     */
    public abstract function deal();

    /**
     * @time 2020年07月29日
     * @param \Throwable $e
     * @return mixed
     */
    public abstract function dealWithException(\Throwable $e);

    /**
     * 执行
     *
     * @time 2020年07月23日
     * @return void|bool
     */
    public function run()
    {
        $startAt = round(microtime(true) * 1000);
        try {
            if ($this->deal() === false) {
                return false;
            }
            $this->recordLog($startAt);
            return true;
        } catch (\Throwable $e) {
            $this->dealWithException($e);
            echo sprintf('[%s]: ', date('Y-m-d H:i:s')) . 'File:' . $e->getFile() . ', Lines: '. $e->getLine() .'行，Exception Message: ' . $e->getMessage() . PHP_EOL;
            // 输出堆栈信息
            echo sprintf('[%s]: ', date('Y-m-d H:i:s')) . $e->getTraceAsString() . PHP_EOL;
            // 日志记录
            $this->recordLog($startAt, 'File:' . $e->getFile() . ', Lines: '. $e->getLine() .'行，Exception Message: ' . $e->getMessage());
            $this->exceptionHappenTimes += 1;
        }


    }

    /**
     * 退出
     *
     * @time 2020年07月29日
     * @return bool
     */
    public function shouldExit()
    {
        // var_dump($this->exceptionHappenTimes);
        return $this->exceptionHappenTimes > $this->exitTimes;
    }

    /**
     * 设置 crontab
     *
     * @time 2020年09月15日
     * @param array $crontab
     * @return $this
     */
    public function setCrontab(array $crontab)
    {
        $this->crontab = $crontab;

        return $this;
    }

    protected function recordLog($startAt, $message = '')
    {
        $endAt = round(microtime(true) * 1000);
        CrontabLog::insert([
            'crontab_id' => $this->crontab['id'],
            'used_time' => $endAt - $startAt,
            'error_message' => $message,
            'status' => $message ? CrontabLog::FAILED : CrontabLog::SUCCESS,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }
}