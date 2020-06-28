<?php

use think\migration\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        \catcher\Utils::importTreeData($this->getPermissions(), 'permissions', 'parent_id');
    }

    protected function getPermissions()
    {
       return array (
  0 => 
  array (
    'id' => 66,
    'permission_name' => '微信管理',
    'parent_id' => 0,
    'level' => '',
    'route' => '/wechat',
    'icon' => 'wechat',
    'module' => 'wechat',
    'creator_id' => 1,
    'method' => 'get',
    'permission_mark' => 'wechat',
    'component' => 'routeView',
    'redirect' => '',
    'hide_children_in_menu' => 2,
    'keepalive' => 1,
    'type' => 1,
    'status' => 1,
    'sort' => 1,
    'created_at' => 1591603025,
    'updated_at' => 1593044101,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 67,
        'permission_name' => '微信菜单',
        'parent_id' => 66,
        'level' => '66',
        'route' => '/wechat/menus',
        'icon' => 'table',
        'module' => 'wechat',
        'creator_id' => 1,
        'method' => 'get',
        'permission_mark' => 'menus',
        'component' => 'menus',
        'redirect' => '',
        'hide_children_in_menu' => 2,
        'keepalive' => 1,
        'type' => 1,
        'status' => 1,
        'sort' => 1,
        'created_at' => 1591603088,
        'updated_at' => 1591603427,
        'deleted_at' => 0,
      ),
      1 => 
      array (
        'id' => 75,
        'permission_name' => '用户管理',
        'parent_id' => 66,
        'level' => '66',
        'route' => '/wechat/users',
        'icon' => 'team',
        'module' => 'wechat',
        'creator_id' => 1,
        'method' => 'get',
        'permission_mark' => 'users',
        'component' => 'pageView',
        'redirect' => '',
        'hide_children_in_menu' => 2,
        'keepalive' => 1,
        'type' => 1,
        'status' => 1,
        'sort' => 1,
        'created_at' => 1592624761,
        'updated_at' => 1592631716,
        'deleted_at' => 0,
        'children' => 
        array (
          0 => 
          array (
            'id' => 76,
            'permission_name' => '微信用户',
            'parent_id' => 75,
            'level' => '66-75',
            'route' => '/wechat/users',
            'icon' => 'user',
            'module' => 'wechat',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'users',
            'component' => 'wechatUsers',
            'redirect' => '',
            'hide_children_in_menu' => 2,
            'keepalive' => 1,
            'type' => 1,
            'status' => 1,
            'sort' => 1,
            'created_at' => 1592624799,
            'updated_at' => 1592624799,
            'deleted_at' => 0,
          ),
          1 => 
          array (
            'id' => 77,
            'permission_name' => '微信标签',
            'parent_id' => 75,
            'level' => '66-75',
            'route' => '/wechat/tags',
            'icon' => 'tags',
            'module' => 'wechat',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'wechatTags',
            'component' => 'wechatTags',
            'redirect' => '',
            'hide_children_in_menu' => 2,
            'keepalive' => 1,
            'type' => 1,
            'status' => 1,
            'sort' => 1,
            'created_at' => 1592722634,
            'updated_at' => 1592812960,
            'deleted_at' => 0,
          ),
        ),
      ),
    ),
  ),
);
    }
}
