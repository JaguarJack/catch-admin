<?php
// 角色
$router->resource('role', '\catchAdmin\permissions\controller\Roles');
// 角色列表
$router->get('roles', '\catchAdmin\permissions\controller\Roles/list');

// 权限
$router->resource('permission', '\catchAdmin\permissions\controller\Permissions');
// 权限列表
$router->get('permissions', '\catchAdmin\permissions\controller\Permissions/list');