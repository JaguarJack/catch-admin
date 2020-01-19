<?php
// 角色
$router->resource('role', '\catchAdmin\permissions\controller\Role');
// 角色列表
$router->get('/role/get/permissions', '\catchAdmin\permissions\controller\Role@getPermissions');
// 权限
$router->resource('permissions', '\catchAdmin\permissions\controller\Permission');
// 部门
$router->resource('departments', '\catchAdmin\permissions\controller\Department');
// 岗位
$router->resource('jobs', '\catchAdmin\permissions\controller\Job');

$router->get('jobs/all', '\catchAdmin\permissions\controller\Job@getAll');
