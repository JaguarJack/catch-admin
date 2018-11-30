<?php

namespace app\admin\controller;

use app\model\UserModel;
use app\admin\request\UserRequest;
use think\permissions\facade\Roles;

class User extends Base
{
	/**
	 * User List
	 *
	 * @time at 2018年11月12日
	 * @return mixed|string
	 */
	public function index(UserModel $userModel)
	{
		$params = $this->request->param();
		$this->checkParams($params);
		$this->users = $userModel->getList($params, $this->limit);

		return $this->fetch();
	}

	/**
	 * create Data
	 *
	 * @time at 2018年11月12日
	 * @return mixed|string
	 */
	public function create(UserModel $userModel, UserRequest $request)
	{
		if ($request->isPost()) {
			$data = $request->post();
			$data['password'] = generatePassword($data['password']);

			if ($userId = $userModel->store($data)) {
				// 分配角色
				$this->giveRoles($userModel, $userId, $data);
				$this->success('添加成功', url('user/index'));
			}
			$this->error('添加失败');
		}

		$this->roles = Roles::all();
		return $this->fetch();
	}

	/**
	 * Edit Data
	 *
	 * @time at 2018年11月12日
	 * @return mixed|string
	 */
	public function edit(UserModel $userModel, UserRequest $request)
	{
		if ($request->isPost()) {
			$data = $request->post();
			$this->giveRoles($userModel, $data['id'], $data);
			$data['password'] = generatePassword($data['password']);
			$userModel->updateBy($data['id'], $data) ? $this->success('修改成功', url('user/index')) : $this->error('修改失败');
		}

		$id = $this->request->param('id');
		if (!$id) {
			$this->error('数据不存在');
		}
		$user = $userModel->findBy($id);
		$userHasRoles = $user->getRoles(false);
		$roles = Roles::all()->each(function($item, $key) use ($userHasRoles){
				$item->checked = in_array($item->id, $userHasRoles) ? true : false;
				return $item;
		});

		$this->user   =  $user;
		$this->roles  = $roles;
		return $this->fetch();
	}

	/**
	 * Delete Data
	 *
	 * @time at 2018年11月12日
	 * @return void
	 */
	public function delete(UserModel $userModel)
	{
		$id = $this->request->post('id');

		if (!$id) {
			$this->error('不存在的数据');
		}
		// 删除用户相关的角色
		$userModel->detachRoles($id);
		if ($userModel->deleteBy($id)) {
			$this->success('删除成功', url('user/index'));
		}
		$this->error('删除失败');
	}

	/**
	 * 分配角色
	 *
	 * @time at 2018年11月15日
	 * @param \app\model\UserModel $userModel
	 * @param int $userId
	 * @param $data
	 * @return bool
	 */
	protected function giveRoles(UserModel $userModel, int $userId, &$data)
	{
		if (isset($data['roles'])) {
			$rolesIds = $data['roles'];
			if (!is_array($rolesIds)) {
				$rolesIds = [$rolesIds];
			}
			$userModel->detachRoles($userId);
			$userModel->attachRoles($userId, $rolesIds);
			unset($data['roles']);
			return true;
		}
		$userModel->detachRoles($userId);
		return true;
	}
}