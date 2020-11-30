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
 * 固定窗口限流
 *
 * Class GrantLimit
 * @package catcher\library\rate
 */
class GrantLimit
{
    use Redis;

    protected $ttl = 60;

    protected $limit = 1000;

    protected $key;

    public function __construct($key)
    {
        $this->key = $key;

        $this->init();
    }

    /**
     * 是否到达限流
     *
     * @time 2020年06月30日
     * @return void
     */
    public function overflow()
    {
        if ($this->getCurrentVisitTimes() > $this->limit) {
            throw new FailedException('访问限制');
        }

        $this->inc();
    }

    /**
     * 增加接口次数
     *
     * @time 2020年06月30日
     * @return void
     */
    public function inc()
    {
        $this->getRedis()->incr($this->key);
    }

    /**
     * 初始化
     *
     * @time 2020年06月30日
     * @return void
     */
    protected function init()
    {
        if (!$this->getRedis()->exists($this->key)) {
            $this->getRedis()->setex($this->key, $this->ttl, 0);
        }
    }

    /**
     * 获取当前访问次数
     *
     * @time 2020年06月30日
     * @return mixed
     */
    protected function getCurrentVisitTimes()
    {
        return  $this->getRedis()->get($this->key);
    }
}