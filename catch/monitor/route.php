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
$router->group('monitor', function () use ($router){
	// crontab路由
	$router->resource('crontab', '\catchAdmin\monitor\controller\Crontab');
    $router->put('crontab/enable/<id>', '\catchAdmin\monitor\controller\Crontab@disOrEnable');

    // crontab 日志
    $router->get('crontab/log/list', '\catchAdmin\monitor\controller\CrontabLog@index');
    $router->delete('crontab/log/list/<id>', '\catchAdmin\monitor\controller\CrontabLog@delete');

})->middleware('auth');