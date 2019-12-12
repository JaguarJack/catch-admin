<?php
// 角色
$router->resource('role', '\catchAdmin\permissions\controller\Role');
// 角色列表
$router->get('roles', '\catchAdmin\permissions\controller\Role@list');
$router->get('/role/get/permissions', '\catchAdmin\permissions\controller\Role@getPermissions');

// 权限
$router->resource('permission', '\catchAdmin\permissions\controller\Permission');
// 权限列表
$router->get('permissions', '\catchAdmin\permissions\controller\Permission@list');
