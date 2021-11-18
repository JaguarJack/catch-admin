<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

// you should use `$router`
/* @var think\Route $router */

$router->group(function () use ($router){
    // apiCategory路由
    $router->resource('apicategory', '\catchAdmin\apimanager\controller\ApiCategory');
    // apiTester路由
    $router->resource('apitester', '\catchAdmin\apimanager\controller\ApiTester');
    // apiTesterUserenv路由
    $router->resource('apiTesterUserenv', '\catchAdmin\apimanager\controller\ApiTesterUserenv');
    // 切换API环境
    $router->get('apiTesterUserenv/selectAPIenv/<id>', '\catchAdmin\apimanager\controller\ApiTesterUserenv@selectAPIenv');
	// apiTesterLog路由
	$router->resource('apiTesterLog', '\catchAdmin\apimanager\controller\ApiTesterLog');
    // routeList 路由
     $router->resource('routeList', catchAdmin\apimanager\controller\RouteList::class);
    $router->post('apimanager/routelist/sync', 'catchAdmin\apimanager\controller\RouteList@sync');
})->middleware('auth');