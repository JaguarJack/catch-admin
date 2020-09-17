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
$router->group('sms', function () use ($router){
	// config路由
	$router->resource('config', '\catchAdmin\sms\controller\Config');
	// template 路由
	$router->resource('template', '\catchAdmin\sms\controller\Template');
})->middleware('auth');