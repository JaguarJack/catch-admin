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
namespace catchAdmin\login;

use catcher\ModuleService;
use think\Service;

class LoginService extends ModuleService
{
    public function loadRouteFrom()
    {
        // TODO: Implement loadRouteFrom() method.
        return __DIR__ . DIRECTORY_SEPARATOR . 'route.php';
    }
}