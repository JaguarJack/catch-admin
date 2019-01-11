<?php
namespace app\admin\controller;

use think\permissions\facade\Roles;
use app\admin\request\RoleRequest;
use think\permissions\facade\Permissions;
use app\service\MenuService;

class Role extends Base
{
    public function index()
    {
    	$this->roles = Roles::paginate(10);
        return $this->fetch();
    }

	/**
	 * create Data
	 *
	 * @time at 2018年11月13日
	 * @return mixed|string
	 */
    public function create(RoleRequest $request)
    {
    	if ($request->isPost()) {
			Roles::store($request->post()) ? $this->success('创建成功', url('role/index')) : $this->error('创建失败');
	    }
        return $this->fetch();
    }

	/**
	 * Edit Data
	 *
	 * @time at 2018年11月13日
	 * @return mixed|string
	 */
    public function edit(RoleRequest $request)
    {
    	if ($this->request->isPost()) {
    		Roles::updateBy($request->post('id'), $request->post()) !== false ? $this->success('编辑成功', url('role/index')) : $this->error('编辑失败');
	    }

    	$this->role = Roles::getRoleBy($this->request->param('id'));
        return $this->fetch();
    }

	/**
	 * Delete Data
	 *
	 * @time at 2018年11月13日
	 * @return void
	 */
    public function delete()
    {
    	$roleId = $this->request->post('id');
    	if (!$roleId) {
    		$this->error('角色信息不存在');
	    }
	    // 删除角色相关的用户
	    Roles::detachUsers($roleId);
	    // 删除角色相关的权限
	    Roles::detachPermissions($roleId);
	    if (Roles::deleteBy($roleId)) {
		    $this->success('删除成功', url('role/index'));
	    }
	   $this->error('删除失败');
    }

	/**
	 * 获取角色权限
	 *
	 * @time at 2018年09月21日
	 * @return void
	 */
	public function getPermissionsOfRole(MenuService $menuService)
	{
		$field = ['name', 'id', 'pid'];
		$roleId = $this->request->post('role_id');
		$permissions = Permissions::field($field)->all();
		$roleHasPermissions = Roles::getRoleBy($roleId)->getPermissions(false);
		$permissions = $permissions->each(function ($item, $key) use ($roleHasPermissions){
				if (!$item->pid) {
					$item->open = true;
				}
				$item->checked = in_array($item->id, $roleHasPermissions) ? true : false;
				return $item;
		});
        
		header('content-Type: application/json');
		exit(json_encode($menuService->sort($permissions)));
	}

	/**
	 * 分配权限
	 *
	 * @time at 2018年11月15日
	 * @return mixed|string
	 */
    public function givePermissions()
    {
    	if ($this->request->isPost()) {
		    $postData = $this->request->post();
		    $roleId      = $postData['role_id'];
		    if (!isset($postData['permissions'])) {
			    Roles::detachPermissions($roleId);
			    $this->success('分配成功', url('role/index'));
		    }
		    $permissions = $postData['permissions'];
		    Roles::detachPermissions($roleId);
		    Roles::attachPermissions($roleId, $permissions) ? $this->success('分配成功', url('role/index')) : $this->error('分配失败');
	    }
    	$this->role_id = $this->request->param('id');
    	return $this->fetch('role/givePermissions');
    }
}