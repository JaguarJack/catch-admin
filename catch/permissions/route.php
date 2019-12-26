<?php
// 角色
$router->resource('roles', '\catchAdmin\permissions\controller\Role');
// 角色列表
$router->get('/role/get/permissions', '\catchAdmin\permissions\controller\Role@getPermissions');

// 权限
$router->resource('permissions', '\catchAdmin\permissions\controller\Permission');
