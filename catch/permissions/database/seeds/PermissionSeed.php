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
        $this->roles();
        $this->createPermissions();
    }

    protected function roles()
    {
        \catchAdmin\permissions\model\Roles::create([
            'role_name' => '超级管理员',
            'description' => 'super user',
            'creator_id' => 1,
        ]);

        \think\facade\Db::name(config('database.connections.mysql.prefix') . 'user_has_roles')->insert([
           'role_id' => 1,
           'uid' => 1,
        ]);

        \think\facade\Db::name(config('database.connections.mysql.prefix') .'role_has_permissions')->insertAll($this->getRolePermissions());
    }


    protected function createPermissions()
    {
        foreach ($this->getPermissions() as $permission) {
            \catchAdmin\permissions\model\Permissions::create($permission);
        }
    }



    protected function getPermissions()
    {
      return array (
        0 =>
          array (
            'id' => 4,
            'permission_name' => 'Dashboard',
            'parent_id' => 0,
            'route' => 'dashboard',
            'icon' => 'home',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'index:dashboard',
            'type' => 1,
            'sort' => 10000,
          ),
        1 =>
          array (
            'id' => 5,
            'permission_name' => '主题',
            'parent_id' => 4,
            'route' => 'themes',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'index:theme',
            'type' => 2,
            'sort' => 0,
          ),
        2 =>
          array (
            'id' => 6,
            'permission_name' => '用户管理',
            'parent_id' => 16,
            'route' => 'user',
            'icon' => 'user',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'user:index',
            'type' => 1,
            'sort' => 9999,
          ),
        3 =>
          array (
            'id' => 7,
            'permission_name' => '创建',
            'parent_id' => 6,
            'route' => 'user',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'user:create',
            'type' => 2,
            'sort' => 0,
          ),
        4 =>
          array (
            'id' => 8,
            'permission_name' => '保存',
            'parent_id' => 6,
            'route' => 'user',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'post',
            'permission_mark' => 'user:save',
            'type' => 2,
            'sort' => 0,
          ),
        5 =>
          array (
            'id' => 9,
            'permission_name' => '查看',
            'parent_id' => 6,
            'route' => 'user/<id>/edit',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'user:edit',
            'type' => 2,
            'sort' => 0,
          ),
        6 =>
          array (
            'id' => 10,
            'permission_name' => '编辑',
            'parent_id' => 6,
            'route' => 'user/<id>',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'user:update',
            'type' => 2,
            'sort' => 0,
          ),
        7 =>
          array (
            'id' => 11,
            'permission_name' => '删除',
            'parent_id' => 6,
            'route' => 'user/<id>',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'delete',
            'permission_mark' => 'user:delete',
            'type' => 2,
            'sort' => 0,
          ),
        8 =>
          array (
            'id' => 12,
            'permission_name' => '列表',
            'parent_id' => 6,
            'route' => 'users',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'user:list',
            'type' => 2,
            'sort' => 0,
          ),
        9 =>
          array (
            'id' => 13,
            'permission_name' => '禁用/启用',
            'parent_id' => 6,
            'route' => 'user/switch/status/<id>',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'user:switchStatus',
            'type' => 2,
            'sort' => 0,
          ),
        10 =>
          array (
            'id' => 14,
            'permission_name' => '恢复',
            'parent_id' => 6,
            'route' => 'user/recover/<id>',
            'icon' => '',
            'module' => 'user',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'user:recover',
            'type' => 2,
            'sort' => 0,
          ),
        11 =>
          array (
            'id' => 15,
            'permission_name' => '角色管理',
            'parent_id' => 16,
            'route' => 'role',
            'icon' => 'usergroup-add',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'role:index',
            'type' => 1,
            'sort' => 1000,
          ),
        12 =>
          array (
            'id' => 16,
            'permission_name' => '权限管理',
            'parent_id' => 0,
            'route' => 'permission',
            'icon' => 'appstore',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => '@:@',
            'type' => 1,
            'sort' => 1,
          ),
        13 =>
          array (
            'id' => 17,
            'permission_name' => '菜单管理',
            'parent_id' => 16,
            'route' => 'permission',
            'icon' => 'build',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'permission:index',
            'type' => 1,
            'sort' => 1,
          ),
        14 =>
          array (
            'id' => 18,
            'permission_name' => '创建',
            'parent_id' => 15,
            'route' => 'role/create',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'role:create',
            'type' => 2,
            'sort' => 1,
          ),
        15 =>
          array (
            'id' => 19,
            'permission_name' => '保存',
            'parent_id' => 15,
            'route' => 'role',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'post',
            'permission_mark' => 'role:save',
            'type' => 2,
            'sort' => 1,
          ),
        16 =>
          array (
            'id' => 20,
            'permission_name' => '查看',
            'parent_id' => 15,
            'route' => 'role/<id>/edit',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'role:edit',
            'type' => 2,
            'sort' => 1,
          ),
        17 =>
          array (
            'id' => 21,
            'permission_name' => '更新',
            'parent_id' => 15,
            'route' => 'role/<id>',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'role:update',
            'type' => 2,
            'sort' => 1,
          ),
        18 =>
          array (
            'id' => 22,
            'permission_name' => '删除',
            'parent_id' => 15,
            'route' => 'role/<id>',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'delete',
            'permission_mark' => 'role:delete',
            'type' => 2,
            'sort' => 1,
          ),
        19 =>
          array (
            'id' => 23,
            'permission_name' => '列表',
            'parent_id' => 15,
            'route' => 'roles',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'role:list',
            'type' => 2,
            'sort' => 1,
          ),
        20 =>
          array (
            'id' => 24,
            'permission_name' => '获取权限',
            'parent_id' => 15,
            'route' => 'role/get/permissions',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'role:getPermissions',
            'type' => 2,
            'sort' => 1,
          ),
        21 =>
          array (
            'id' => 25,
            'permission_name' => '删除',
            'parent_id' => 17,
            'route' => 'permission/<id>',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'delete',
            'permission_mark' => 'permission:delete',
            'type' => 2,
            'sort' => 1,
          ),
        22 =>
          array (
            'id' => 26,
            'permission_name' => '更新',
            'parent_id' => 17,
            'route' => 'permission/<id>',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'permission:update',
            'type' => 2,
            'sort' => 1,
          ),
        23 =>
          array (
            'id' => 27,
            'permission_name' => '查看',
            'parent_id' => 17,
            'route' => 'permissions/<id>/edit',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'permission:edit',
            'type' => 2,
            'sort' => 1,
          ),
        24 =>
          array (
            'id' => 28,
            'permission_name' => '创建',
            'parent_id' => 17,
            'route' => 'permission/create',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'permission:create',
            'type' => 2,
            'sort' => 1,
          ),
        25 =>
          array (
            'id' => 29,
            'permission_name' => '保存',
            'parent_id' => 17,
            'route' => 'permission',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'post',
            'permission_mark' => 'permission:save',
            'type' => 2,
            'sort' => 1,
          ),
        26 =>
          array (
            'id' => 30,
            'permission_name' => '列表',
            'parent_id' => 17,
            'route' => 'permissions',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'permission:index',
            'type' => 2,
            'sort' => 1,
          ),
        27 =>
          array (
            'id' => 31,
            'permission_name' => '系统管理',
            'parent_id' => 0,
            'route' => '',
            'icon' => '',
            'module' => 'permissions',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 's:s',
            'type' => 1,
            'sort' => 1,
          ),
        28 =>
          array (
            'id' => 32,
            'permission_name' => '日志管理',
            'parent_id' => 31,
            'route' => 'log',
            'icon' => 'laptop',
            'module' => 'system',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'log:log',
            'type' => 1,
            'sort' => 1,
          ),
        29 =>
          array (
            'id' => 33,
            'permission_name' => '登录日志',
            'parent_id' => 32,
            'route' => 'loginLog/index',
            'icon' => '',
            'module' => 'system',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'operateLog:index',
            'type' => 1,
            'sort' => 1,
          ),
        30 =>
          array (
            'id' => 34,
            'permission_name' => '操作日志',
            'parent_id' => 32,
            'route' => 'operateLog/index',
            'icon' => '',
            'module' => 'index',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'loginLog:index',
            'type' => 1,
            'sort' => 1,
          ),
        31 =>
          array (
            'id' => 35,
            'permission_name' => '数据字典',
            'parent_id' => 31,
            'route' => 'data/dictionary',
            'icon' => 'hdd',
            'module' => 'system',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'datadictory:index',
            'type' => 1,
            'sort' => 1,
          ),
        32 =>
          array (
            'id' => 36,
            'permission_name' => '查看表',
            'parent_id' => 35,
            'route' => 'table/view/<table>',
            'icon' => '',
            'module' => 'system',
            'creator_id' => 1,
            'method' => 'get',
            'permission_mark' => 'datadictionary:view',
            'type' => 2,
            'sort' => 1,
          ),
        33 =>
          array (
            'id' => 37,
            'permission_name' => '部门管理',
            'parent_id' => 16,
            'route' => 'departments',
            'icon' => 'desktop',
            'module' => '',
            'creator_id' => 1,
            'method' => 'GET',
            'permission_mark' => 'departments:index',
            'type' => 2,
            'sort' => 1,
          ),
        34 =>
          array (
            'id' => 38,
            'permission_name' => '新增',
            'parent_id' => 37,
            'route' => 'departments',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'post',
            'permission_mark' => 'department:save',
            'type' => 2,
            'sort' => 1,
          ),
        35 =>
          array (
            'id' => 39,
            'permission_name' => '列表',
            'parent_id' => 37,
            'route' => 'departments',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'GET',
            'permission_mark' => 'department:index',
            'type' => 2,
            'sort' => 2,
          ),
        36 =>
          array (
            'id' => 40,
            'permission_name' => '编辑',
            'parent_id' => 37,
            'route' => 'departments/<id>',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'department:edit',
            'type' => 2,
            'sort' => 1,
          ),
        37 =>
          array (
            'id' => 41,
            'permission_name' => '删除',
            'parent_id' => 37,
            'route' => 'departments/<id>',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'delete',
            'permission_mark' => 'department:delete',
            'type' => 2,
            'sort' => 1,
          ),
        38 =>
          array (
            'id' => 42,
            'permission_name' => '岗位管理',
            'parent_id' => 16,
            'route' => 'jobs',
            'icon' => 'skin',
            'module' => '',
            'creator_id' => 1,
            'method' => 'GET',
            'permission_mark' => 'jobs:index',
            'type' => 1,
            'sort' => 1,
          ),
        39 =>
          array (
            'id' => 43,
            'permission_name' => '列表',
            'parent_id' => 42,
            'route' => 'jobs',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'GET',
            'permission_mark' => 'job:index',
            'type' => 1,
            'sort' => 5,
          ),
        40 =>
          array (
            'id' => 44,
            'permission_name' => '新增',
            'parent_id' => 42,
            'route' => 'jobs',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'post',
            'permission_mark' => 'job:save',
            'type' => 2,
            'sort' => 1,
          ),
        41 =>
          array (
            'id' => 45,
            'permission_name' => '编辑',
            'parent_id' => 42,
            'route' => 'jobs/<id>',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'put',
            'permission_mark' => 'job:edit',
            'type' => 2,
            'sort' => 1,
          ),
        42 =>
          array (
            'id' => 46,
            'permission_name' => '删除',
            'parent_id' => 42,
            'route' => 'jobs/<id>',
            'icon' => '',
            'module' => '',
            'creator_id' => 1,
            'method' => 'delete',
            'permission_mark' => 'job:delete',
            'type' => 2,
            'sort' => 1,
          ),
      );
    }

    protected function getRolePermissions()
    {
      return array (
        0 =>
          array (
            'id' => 70,
            'role_id' => 1,
            'permission_id' => 4,
          ),
        1 =>
          array (
            'id' => 71,
            'role_id' => 1,
            'permission_id' => 6,
          ),
        2 =>
          array (
            'id' => 72,
            'role_id' => 1,
            'permission_id' => 7,
          ),
        3 =>
          array (
            'id' => 73,
            'role_id' => 1,
            'permission_id' => 8,
          ),
        4 =>
          array (
            'id' => 74,
            'role_id' => 1,
            'permission_id' => 9,
          ),
        5 =>
          array (
            'id' => 75,
            'role_id' => 1,
            'permission_id' => 10,
          ),
        6 =>
          array (
            'id' => 76,
            'role_id' => 1,
            'permission_id' => 11,
          ),
        7 =>
          array (
            'id' => 77,
            'role_id' => 1,
            'permission_id' => 12,
          ),
        8 =>
          array (
            'id' => 78,
            'role_id' => 1,
            'permission_id' => 13,
          ),
        9 =>
          array (
            'id' => 79,
            'role_id' => 1,
            'permission_id' => 14,
          ),
        10 =>
          array (
            'id' => 80,
            'role_id' => 1,
            'permission_id' => 15,
          ),
        11 =>
          array (
            'id' => 81,
            'role_id' => 1,
            'permission_id' => 16,
          ),
        12 =>
          array (
            'id' => 82,
            'role_id' => 1,
            'permission_id' => 17,
          ),
        13 =>
          array (
            'id' => 83,
            'role_id' => 1,
            'permission_id' => 18,
          ),
        14 =>
          array (
            'id' => 84,
            'role_id' => 1,
            'permission_id' => 19,
          ),
        15 =>
          array (
            'id' => 85,
            'role_id' => 1,
            'permission_id' => 20,
          ),
        16 =>
          array (
            'id' => 86,
            'role_id' => 1,
            'permission_id' => 21,
          ),
        17 =>
          array (
            'id' => 87,
            'role_id' => 1,
            'permission_id' => 22,
          ),
        18 =>
          array (
            'id' => 88,
            'role_id' => 1,
            'permission_id' => 23,
          ),
        19 =>
          array (
            'id' => 89,
            'role_id' => 1,
            'permission_id' => 24,
          ),
        20 =>
          array (
            'id' => 90,
            'role_id' => 1,
            'permission_id' => 25,
          ),
        21 =>
          array (
            'id' => 91,
            'role_id' => 1,
            'permission_id' => 26,
          ),
        22 =>
          array (
            'id' => 92,
            'role_id' => 1,
            'permission_id' => 27,
          ),
        23 =>
          array (
            'id' => 93,
            'role_id' => 1,
            'permission_id' => 28,
          ),
        24 =>
          array (
            'id' => 94,
            'role_id' => 1,
            'permission_id' => 29,
          ),
        25 =>
          array (
            'id' => 95,
            'role_id' => 1,
            'permission_id' => 30,
          ),
        26 =>
          array (
            'id' => 96,
            'role_id' => 1,
            'permission_id' => 31,
          ),
        27 =>
          array (
            'id' => 97,
            'role_id' => 1,
            'permission_id' => 32,
          ),
        28 =>
          array (
            'id' => 98,
            'role_id' => 1,
            'permission_id' => 33,
          ),
        29 =>
          array (
            'id' => 99,
            'role_id' => 1,
            'permission_id' => 34,
          ),
        30 =>
          array (
            'id' => 100,
            'role_id' => 1,
            'permission_id' => 35,
          ),
        31 =>
          array (
            'id' => 101,
            'role_id' => 1,
            'permission_id' => 36,
          ),
        32 =>
          array (
            'id' => 102,
            'role_id' => 1,
            'permission_id' => 37,
          ),
        33 =>
          array (
            'id' => 103,
            'role_id' => 1,
            'permission_id' => 38,
          ),
        34 =>
          array (
            'id' => 104,
            'role_id' => 1,
            'permission_id' => 39,
          ),
        35 =>
          array (
            'id' => 105,
            'role_id' => 1,
            'permission_id' => 40,
          ),
        36 =>
          array (
            'id' => 106,
            'role_id' => 1,
            'permission_id' => 41,
          ),
        37 =>
          array (
            'id' => 107,
            'role_id' => 1,
            'permission_id' => 42,
          ),
        38 =>
          array (
            'id' => 108,
            'role_id' => 1,
            'permission_id' => 43,
          ),
        39 =>
          array (
            'id' => 109,
            'role_id' => 1,
            'permission_id' => 44,
          ),
        40 =>
          array (
            'id' => 110,
            'role_id' => 1,
            'permission_id' => 45,
          ),
        41 =>
          array (
            'id' => 111,
            'role_id' => 1,
            'permission_id' => 46,
          ),
      );
    }
}
