<?php
// 登录日志
$router->get('log/login', '\catchAdmin\system\controller\LoginLog@list');
$router->get('loginLog/index', '\catchAdmin\system\controller\LoginLog@index');
$router->delete('loginLog/empty', '\catchAdmin\system\controller\LoginLog@empty');
// 操作日志
$router->get('log/operate', '\catchAdmin\system\controller\OperateLog@list');
$router->get('operateLog/index', '\catchAdmin\system\controller\OperateLog@index');
$router->delete('operateLog/empty', '\catchAdmin\system\controller\OperateLog@empty');

// 数据字典
$router->get('tables', '\catchAdmin\system\controller\DataDictionary@tables');
$router->get('table/view/<table>', '\catchAdmin\system\controller\DataDictionary@view');
$router->post('table/optimize', '\catchAdmin\system\controller\DataDictionary@optimize');
$router->post('table/backup', '\catchAdmin\system\controller\DataDictionary@backup');

// 上传
$router->post('upload/image', '\catchAdmin\system\controller\Upload@image');
$router->post('upload/file', '\catchAdmin\system\controller\Upload@file');
