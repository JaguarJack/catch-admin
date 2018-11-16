<?php
/**
 * permissions.php
 * Created by wuyanwen <wuyanwen1992@gmail.com>
 * Date: 2018/9/26 0026 20:23
 */

return [
	'table' => [
		'permission'		   => 'permissions',
		'role'	               => 'roles',
		'user_has_roles'       => 'user_has_roles',
		'role_has_permissions' => 'role_has_permissions',
	],

	'model' => [
		'permission' => think\permissions\model\Permissions::class,
		'role'		 => think\permissions\model\Roles::class,
		// Must set User Model Class
		'user'		 => app\model\UserModel::class,
	],

	// Login User Session Key
	'user' => 'user',
];
