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
    // tradeHall路由
    $router->rule('trade/hall/layout', '\catchAdmin\trade\controller\tradeHall@layout');
    $router->resource('trade/hall', '\catchAdmin\trade\controller\tradeHall');
    // tradeRecharge路由
    $router->rule('trade/recharge/layout', '\catchAdmin\trade\controller\tradeRecharge@layout');
    $router->resource('trade/recharge', '\catchAdmin\trade\controller\tradeRecharge');
    // tradeWithdrawal路由
    $router->rule('trade/withdrawal/layout', '\catchAdmin\trade\controller\tradeWithdrawal@layout');
    $router->resource('trade/withdrawal', '\catchAdmin\trade\controller\tradeWithdrawal');
    // tradeConfig路由
    // $router->rule('trade/config/layout', '\catchAdmin\trade\controller\tradeConfig@layout');
    $router->resource('trade/config', '\catchAdmin\trade\controller\tradeConfig');
})->middleware('auth');
