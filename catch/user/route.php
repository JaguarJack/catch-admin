<?php

$router->resource('user', '\catchAdmin\user\controller\User');
// 切换状态
$router->put('user/switch/status/<id>', '\catchAdmin\user\controller\User@switchStatus');
$router->put('user/recover/<id>', '\catchAdmin\user\controller\User@recover');
$router->get('user/get/roles', '\catchAdmin\user\controller\User@getRoles');
