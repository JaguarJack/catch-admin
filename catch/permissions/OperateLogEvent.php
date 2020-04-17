<?php

namespace catchAdmin\permissions;

use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
use think\facade\Db;

class OperateLogEvent
{
    public function handle($params)
    {
        $permission = $params['permission'];

        $parentPermission = Permissions::where('id', $permission->parent_id)->value('permission_name');

        $requestParams = request()->param();
        Db::name('operate_log')->insert([
            'creator_id' => $params['creator_id'],
            'module'     => $parentPermission ? : '',
            'method'     => request()->method(),
            'operate'    => $permission->permission_name,
            'route'      => $permission->route,
            'params'     => !empty($requestParams) ? json_encode($requestParams, JSON_UNESCAPED_UNICODE) : '',
            'created_at' => time(),
            'ip'         => request()->ip(),
        ]);
    }
}
