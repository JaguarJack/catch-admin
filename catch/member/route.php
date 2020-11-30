<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

// you should use `$router`
$router->group(function () use ($router) {
    // member路由
    $router->rule('/member/member/layout', '\catchAdmin\member\controller\Member@layout');
    $router->resource('/member/member', '\catchAdmin\member\controller\Member');
    // level路由
    $router->rule('/member/level/layout', '\catchAdmin\member\controller\Level@layout');
    $router->resource('/member/level', '\catchAdmin\member\controller\Level');
})->middleware('auth');
