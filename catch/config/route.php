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
    // financeConfig路由
    $router->rule('finance/config/layout', '\catchAdmin\config\controller\FinanceConfig@layout');
    $router->resource('finance/config', '\catchAdmin\config\controller\FinanceConfig');
    // appConfig路由
    $router->rule('app/config/layout', '\catchAdmin\config\controller\AppConfig@layout');
    $router->resource('app/config', '\catchAdmin\config\controller\AppConfig');
})->middleware('auth');
