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
        ]);

        \think\facade\Db::name('user_has_roles')->insert([
           'role_id' => 1,
           'uid' => 1,
        ]);

        \think\facade\Db::name('role_has_permissions')->insertAll(array (
            0 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 4,
                ),
            1 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 6,
                ),
            2 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 7,
                ),
            3 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 8,
                ),
            4 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 9,
                ),
            5 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 10,
                ),
            6 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 11,
                ),
            7 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 12,
                ),
            8 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 13,
                ),
            9 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 14,
                ),
            10 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 15,
                ),
            11 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 16,
                ),
            12 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 17,
                ),
            13 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 18,
                ),
            14 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 19,
                ),
            15 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 20,
                ),
            16 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 21,
                ),
            17 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 22,
                ),
            18 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 23,
                ),
            19 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 24,
                ),
            20 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 25,
                ),
            21 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 26,
                ),
            22 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 27,
                ),
            23 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 28,
                ),
            24 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 29,
                ),
            25 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 30,
                ),
            26 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 31,
                ),
            27 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 32,
                ),
            28 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 33,
                ),
            29 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 34,
                ),
            30 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 35,
                ),
            31 =>
                array (
                    'role_id' => 1,
                    'permission_id' => 36,
                ),
        ));
    }


    protected function createPermissions()
    {
        foreach (array (
            0 =>
                array (
                    'id' => 4,
                    'permission_name' => 'Dashboard',
                    'parent_id' => 0,
                    'module' => 'index',
                    'route' => 'dashboard',
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
                    'module' => '',
                    'route' => 'themes',
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
                    'module' => 'user',
                    'route' => 'user',
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
                    'module' => 'user',
                    'route' => 'user',
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
                    'module' => 'user',
                    'route' => 'user',
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
                    'module' => 'user',
                    'route' => 'user/<id>/edit',
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
                    'module' => 'user',
                    'route' => 'user/<id>',
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
                    'module' => 'user',
                    'route' => 'user/<id>',
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
                    'module' => 'user',
                    'route' => 'users',
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
                    'module' => 'user',
                    'route' => 'user/switch/status/<id>',
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
                    'module' => 'user',
                    'route' => 'user/recover/<id>',
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
                    'module' => 'permissions',
                    'route' => 'role',
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
                    'module' => 'permissions',
                    'route' => '',
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
                    'module' => 'permissions',
                    'route' => 'permission',
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
                    'module' => 'permissions',
                    'route' => 'role/create',
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
                    'module' => 'permissions',
                    'route' => 'role',
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
                    'module' => 'permissions',
                    'route' => 'role/<id>/edit',
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
                    'module' => 'permissions',
                    'route' => 'role/<id>',
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
                    'module' => 'permissions',
                    'route' => 'role/<id>',
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
                    'module' => 'permissions',
                    'route' => 'roles',
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
                    'module' => 'permissions',
                    'route' => 'role/get/permissions',
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
                    'module' => 'permissions',
                    'route' => 'permission/<id>',
                    'method' => 'delete',
                    'permission_mark' => 'permissions:delete',
                    'type' => 2,
                    'sort' => 1,
                ),
            22 =>
                array (
                    'id' => 26,
                    'permission_name' => '更新',
                    'parent_id' => 17,
                    'module' => 'permissions',
                    'route' => 'permission/<id>',
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
                    'module' => 'permissions',
                    'route' => 'permissions/<id>/edit',
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
                    'module' => 'permissions',
                    'route' => 'permission/create',
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
                    'module' => 'permissions',
                    'route' => 'permission',
                    'method' => 'post',
                    'permission_mark' => 'permission',
                    'type' => 2,
                    'sort' => 1,

                ),
            26 =>
                array (
                    'id' => 30,
                    'permission_name' => '列表',
                    'parent_id' => 17,
                    'module' => 'permissions',
                    'route' => 'permissions',
                    'method' => 'get',
                    'permission_mark' => 'permission:list',
                    'type' => 2,
                    'sort' => 1,
                ),
            27 =>
                array (
                    'id' => 31,
                    'permission_name' => '系统管理',
                    'parent_id' => 0,
                    'module' => 'system',
                    'route' => '',
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
                    'module' => 'system',
                    'route' => '',
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
                    'module' => 'system',
                    'route' => 'loginLog/index',
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
                    'module' => 'index',
                    'route' => 'operateLog/index',
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
                    'module' => 'system',
                    'route' => 'data/dictionary',
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
                    'module' => 'system',
                    'route' => 'table/view/<table>',
                    'method' => 'get',
                    'permission_mark' => 'datadictionary:view',
                    'type' => 2,
                    'sort' => 1,
                ),
        ) as $permission) {
            \catchAdmin\permissions\model\Permissions::create($permission);
        }
    }

}