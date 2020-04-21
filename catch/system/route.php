<?php
// 登录日志
$router->get('log/login', '\catchAdmin\system\controller\LoginLog@list');
$router->delete('loginLog/empty', '\catchAdmin\system\controller\LoginLog@empty');
// 操作日志
$router->get('log/operate', '\catchAdmin\system\controller\OperateLog@list');
$router->delete('operateLog/empty', '\catchAdmin\system\controller\OperateLog@empty');

// 数据字典
$router->get('tables', '\catchAdmin\system\controller\DataDictionary@tables');
$router->get('table/view/<table>', '\catchAdmin\system\controller\DataDictionary@view');
$router->post('table/optimize', '\catchAdmin\system\controller\DataDictionary@optimize');
$router->post('table/backup', '\catchAdmin\system\controller\DataDictionary@backup');

// 上传
$router->post('upload/image', '\catchAdmin\system\controller\Upload@image');
$router->post('upload/file', '\catchAdmin\system\controller\Upload@file');

// 附件
$router->resource('attachments', '\catchAdmin\system\controller\Attachments');

// 配置
$router->get('config/parent', '\catchAdmin\system\controller\Config@parent');
$router->resource('config', '\catchAdmin\system\controller\Config');