<?php
# 这是一个 demo 控制器
# 可以删除
# 只做演示用

namespace Modules\Common\Http\Controllers;

use Catch\Base\CatchController;
use Illuminate\Http\Request;
use Modules\Common\Tables\Permission;
use Modules\Common\Tables\Role;

/**
 * @group 公共模块
 *
 * @subgroup 公共演示
 * @subgroupDescription CatchAdmin 后台公共演示
 */
class DynamicController extends CatchController
{

    public function permission(Permission $permission, Request $request)
    {
        return $permission($request->get('key'));
    }

    public function role(Role $role, Request $request)
    {
        return $role($request->get('key'));
    }
}
