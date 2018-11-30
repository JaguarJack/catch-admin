<?php
namespace app\admin\controller;

use think\Collection;
use think\permissions\facade\Permissions;
use app\admin\request\PermissionRequest;
use app\service\MenuService;

class Permission extends Base
{
    public function index(MenuService $menuService)
    {
    	$this->permissions = new Collection($menuService->sort(Permissions::select()));
        return $this->fetch();
    }

	/**
	 * Create Data
	 *
	 * @time at 2018年11月13日
	 * @return mixed|string
	 */
    public function create(PermissionRequest $request, MenuService $menuService)
    {
    	if ($request->isPost()) {
    		$data = $request->post();
    		Permissions::store($data) ? $this->success('添加成功', url('permission/index')) : $this->error('添加失败');
	    }

	    $this->permissions = $menuService->sort(Permissions::select());
    	$this->permissionId  = $this->request->param('id') ?? 0;
        return $this->fetch();
    }

	/**
	 * Edit Data
	 *
	 * @time at 2018年11月13日
	 * @return mixed|string
	 */
    public function edit(PermissionRequest $request, MenuService $menuService)
    {
    	if ($request->isPost()) {
    		$data = $request->post();
    		Permissions::updateBy($data['id'], $data) !== false ? $this->success('编辑成功', url('permission/index')) : $this->error('');
	    }
    	$permissionId = $this->request->param('id');
    	if (!$permissionId) {
    		$this->error('不存在的数据');
	    }
	    $this->permissions = $menuService->sort(Permissions::select());
    	$this->permission = Permissions::getPermissionBy($permissionId);
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
    	$permissionId = $this->request->post('id');
    	if (!$permissionId) {
    		$this->error('不存在数据');
	    }
	    if (Permissions::where('pid', $permissionId)->find()) {
    		$this->error('请先删除子菜单');
	    }
	    // 删除权限关联的角色信息
	    Permissions::detachRole($permissionId);
	    if (Permissions::deleteBy($permissionId)) {
		    $this->success('删除成功', url('permission/index'));
	    }
	    $this->error('删除失败');
    }
}