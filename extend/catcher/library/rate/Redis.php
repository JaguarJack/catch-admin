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
namespace catcher\library\rate;

use think\facade\Cache;

trait Redis
{
    protected $redis = null;

    protected function getRedis()
    {
        if (!$this->redis) {
            $this->redis = Cache::store('redis')->handler();
        }

        return $this->redis;
    }

    /**
     * 设置 ttl
     *
     * @time 2020年06月30日
     * @param $ttl
     * @return $this
     */
    public function ttl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * 设置限制次数
     *
     * @time 2020年06月30日
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}