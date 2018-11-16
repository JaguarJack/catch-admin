<?php

namespace app\admin\controller;

use think\permissions\facade\Permissions;
use think\permissions\facade\Roles;
use app\service\MenuService;

class Index extends Base
{
	protected $middleware = [ 'checkLogin' ];

	/**
	 * 首页
	 *
	 * @time at 2018年11月15日
	 * @return mixed|string
	 */
    public function index(MenuService $menuService)
    {
    	$loginUser = $this->getLoginUser();
    	$userHasRoles = $loginUser->getRoles();
    	$permissionIds = [];
		$userHasRoles->each(function ($role, $key) use (&$permissionIds) {
			$permissionIds = array_merge($permissionIds, Roles::getRoleBy($role->id)->getPermissions(false));
		});
		$permissions = Permissions::whereIn('id', $permissionIds)->where('is_show', 1)->select();
		$this->permissions = $menuService->tree($permissions);
		$this->loginUser   = $loginUser;
        return $this->fetch();
    }

	/**
	 * main
	 *
	 * @time at 2018年11月16日
	 * @return mixed|string
	 */
    public function main()
    {
    	return $this->fetch();
    }
}