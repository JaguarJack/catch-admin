<?php
$router->resource('role', '\catchAdmin\permissions\controller\Roles');
// 用户列表
$router->get('roles', '\catchAdmin\permissions\controller\Roles/list');
