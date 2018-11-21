<?php

use think\migration\Seeder;

class Permissions extends Seeder
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
	   $data =  [
	                [
				   'id' => 1,
				  'name' => '权限管理',
				   'icon' => '',
				   'pid' => 0,
				   'module' => '',
				   'controller' => '',
				   'action' => '',
				   'is_show' => 1,
	                ],

			  [
			    	'id'  => 2,
				    'name' => '用户管理',
				    'icon' => '',
				    'pid' => 1,
				    'module' => 'admin',
				    'controller' => 'user',
				    'action' => 'index',
				    'is_show' => 1,
			  ],

			  [
				    'id' => 3,
				    'name' => '角色管理',
				    'icon' => '',
				    'pid' => 1,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'index',
				    'is_show' => 1,
			  ],

			 [
				    'id' => 4,
				    'name' => '菜单管理',
				    'icon' => '',
				    'pid' => 1,
				    'module' => 'admin',
				    'controller' => 'permission',
				    'action' => 'index',
				    'is_show' => 1,
			 ],

			   [
				    'id' => 5,
				    'name' => '创建用户',
				    'icon' => '',
				    'pid' => 2,
				    'module' => 'admin',
				    'controller' => 'user',
				    'action' => 'create',
				    'is_show' => 2,
			   ],

			   [
				    'id' => 6,
				    'name' => '编辑用户',
				    'icon' => '',
				    'pid' => 2,
				    'module' => 'admin',
				    'controller' => 'user',
				    'action' => 'edit',
				    'is_show' => 2,
			   ],

			[
				    'id' => 7,
				    'name' => '删除用户',
				    'icon' => '',
				    'pid' => 2,
				    'module' => 'admin',
				    'controller' => 'user',
				    'action' => 'delete',
				    'is_show' => 2,
			],

		[
				    'id' => 8,
				    'name' => '创建角色',
				    'icon' => '',
				    'pid' => 3,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'create',
				    'is_show' => 2,
		],

			   [
				    'id' => 9,
				    'name' => '编辑角色',
				    'icon' => '',
				    'pid' => 3,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'edit',
				    'is_show' => 2,
			   ],

			[
				    'id' => 10,
				    'name' => '删除角色',
				    'icon' => '',
				    'pid' => 3,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'delete',
				    'is_show' => 2,
			],

			[
				    'id' => 11,
				    'name' => '获取角色权限',
				    'icon' => '',
				    'pid' => 3,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'getPermissionsOfRole',
				    'is_show' => 2,
			],

			 [
				    'id' => 12,
				    'name' => '分配权限',
				    'icon' => '',
				    'pid' => 3,
				    'module' => 'admin',
				    'controller' => 'role',
				    'action' => 'givePermissions',
				    'is_show' => 2,
			 ],

			 [
				    'id' => 13,
				    'name' => '分配角色',
				    'icon' => '',
				    'pid' => 2,
				    'module' => 'admin',
				    'controller' => 'user',
				    'action' => 'giveRoles',
				    'is_show' => 2,
			 ],

			 [
				    'id' => 14,
				    'name' => '创建菜单',
				    'icon' => '',
				    'pid' => 4,
				    'module' => 'admin',
				    'controller' => 'permission',
				    'action' => 'create',
				    'is_show' => 2,
			 ],

			 [
				    'id' => 15,
				    'name' => '编辑菜单',
				    'icon' => '',
				    'pid' => 4,
				    'module' => 'admin',
				    'controller' => 'permission',
				    'action' => 'edit',
				    'is_show' => 2,
			 ],

			    [
				    'id' => 16,
				    'name' => '删除菜单',
				    'icon' => '',
				    'pid' => 4,
				    'module' => 'admin',
				    'controller' => 'permission',
				    'action' => 'delete',
				    'is_show' => 2,
                ],

	    ];

			$this->table(config('permissions.table.permission'))->insert($data)->save();


    }
}