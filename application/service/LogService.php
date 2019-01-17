<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
 * Time: 18:06
 */
namespace app\service;

use think\permissions\facade\Permissions;
use think\Request;
use app\model\LogRecordModel;

class LogService
{

    public function record(Request $request)
    {
        $module = $request->module();
        $controller = $request->controller();
        $action = $request->action();
        $user = $request->session('user');
        $permission = Permissions::getPermissionByModuleAnd($module, $controller, $action);

        (new LogRecordModel())->store([
           'user_id'     => $user->id,
           'user_name'   => $user->name,
            'module'     => $module,
            'controller' => $controller,
            'action'     => $action,
            'option'     => $permission->name,
            'method'     => $request->method(),
        ]);
    }
}
