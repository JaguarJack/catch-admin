<?php

$router->resource('users', '\catchAdmin\user\controller\User');
// 切换状态
$router->put('users/switch/status/<id>', '\catchAdmin\user\controller\User@switchStatus');
$router->put('users/recover/<id>', '\catchAdmin\user\controller\User@recover');
$router->get('users/get/roles', '\catchAdmin\user\controller\User@getRoles');
$router->get('user/info', '\catchAdmin\user\controller\User@info');
