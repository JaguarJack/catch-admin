<?php

namespace catchAdmin\permissions;

use think\facade\Db;

class OperateLogListener
{
    public function handle($params)
    {
        Db::name('operate_log')->insert([
            'module'     => $params['module'],
            'ip'         => request()->ip(),
            'operate'    => $params['operate'],
            'creator_id' => $params['uid'],
            'method'     => $params['method'],
            'created_at' => time(),
        ]);
    }
}
