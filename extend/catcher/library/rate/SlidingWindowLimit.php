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
namespace catcher\library\rate;

use catcher\exceptions\FailedException;

/**
 * 滑动窗口
 *
 * Class SlidingWindowLimit
 * @package catcher\library\rate
 */
class SlidingWindowLimit
{
    use Redis;

    protected $key;

    protected $limit = 10;

    /**
     * @var int
     */
    protected $window = 5;

    public function __construct($key)
    {
        $this->key = $key;
    }


    public function overflow()
    {
        $now = microtime(true) * 1000;

        $redis = $this->getRedis();
        // 开启管道
        $redis->pipeline();
        // 去除非窗口内的元素
        $redis->zremrangeByScore($this->key, 0, $now - $this->window*1000);
        // 获取集合内的所有元素数目
        $redis->zcard($this->key);
        // 增加元素
        $redis->zadd($this->key, $now, $now);
        // 设置过期
        $redis->expire($this->key, $this->window);
        // 执行管道内命令
        $res = $redis->exec();

        if ($res[1] > $this->limit) {
            throw new FailedException('访问限制');
        }

        return true;
    }

    public function setWindow($time)
    {
        $this->window = $time;

        return $this;
    }
}