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
use catchAdmin\domain\controller\DomainRecord;

$router->group(function () use ($router){
    // 域名管理
    $router->get('domain',  '\catchAdmin\domain\controller\Domain@index');
    $router->get('domain/<name>',  '\catchAdmin\domain\controller\Domain@read');
    // 域名解析管理
    $router->resource('record/domain', DomainRecord::class);
    $router->put('record/domain/<id>/<status>', '\catchAdmin\domain\controller\DomainRecord@enable');
})->middleware('auth');

