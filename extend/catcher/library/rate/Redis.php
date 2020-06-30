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
namespace catcher\library;

use think\facade\Cache;

class Redis
{
    protected $handle = null;


    public function __construct()
    {
        if (!$this->handle) {
            $this->handle = Cache::store('redis')->handler();
        }

        return $this->handle;
    }



    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->handle->{$name}(...$arguments);
    }
}