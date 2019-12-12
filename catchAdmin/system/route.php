<?php
// 登录日志
$router->get('log/login', '\catchAdmin\system\controller\LoginLog@list');
$router->get('loginLog/index', '\catchAdmin\system\controller\LoginLog@index');
// 操作日志
$router->get('log/operate', '\catchAdmin\system\controller\OperateLog@list');
$router->get('operateLog/index', '\catchAdmin\system\controller\OperateLog@index');

