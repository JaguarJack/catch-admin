<?php
namespace catchAdmin\permissions\controller;

use catchAdmin\permissions\excel\UserExport;
use catcher\base\CatchRequest as Request;
use catchAdmin\permissions\model\Permissions;
use catchAdmin\permissions\model\Roles;
use catchAdmin\permissions\model\Users;
use catchAdmin\permissions\request\CreateRequest;
use catchAdmin\permissions\request\UpdateRequest;
use catchAdmin\permissions\request\ProfileRequest;
use catcher\base\CatchController;
use catcher\CatchAuth;
use catcher\CatchCacheKeys;
use catcher\CatchResponse;
use catcher\library\excel\Excel;
use catcher\Tree;
use catcher\Utils;
use think\facade\Cache;

class User extends CatchController
{
    protected $user;

    public function __construct(Users $user)
    {
       $this->user = $user;
    }

    /**
     *
     * @time 2020年04月24日
     * @throws \think\db\exception\DbException
     * @return \think\response\Json
     */
    public function index()
    {
        return CatchResponse::paginate($this->user->getList());
    }

  /**
   * 获取用户信息
   *
   * @time 2020年01月07日
   * @param CatchAuth $auth
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\DbException
   * @throws \think\db\exception\ModelNotFoundException
   * @return \think\response\Json
   */
    public function info(CatchAuth $auth)
    {
        $user = $auth->user();

        $roles = $user->getRoles()->column('identify');

        $permissionIds = $user->getPermissionsBy($user->id);
        // 缓存用户权限
        Cache::set(CatchCacheKeys::USER_PERMISSIONS . $user->id, $permissionIds);

        $user->permissions = Permissions::getCurrentUserPermissions($permissionIds);

        $user->roles = $roles;

        // 用户数据权限
        // $user->data_range = Roles::getDepartmentUserIdsBy($roles);

        return CatchResponse::success($user);
    }

    /**
     *
     * @param CreateRequest $request
     * @time 2019年12月06日
     * @return \think\response\Json
     */
    public function save(CreateRequest $request)
    {
        $this->user->storeBy($request->param());

        $this->user->attachRoles($request->param('roles'));

        if ($request->param('jobs')) {
            $this->user->attachJobs($request->param('jobs'));
        }

        return CatchResponse::success('', '添加成功');
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function read($id)
    {
        $user = $this->user->findBy($id);
        $user->roles = $user->getRoles();
        $user->jobs  = $user->getJobs();
        return CatchResponse::success($user);
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @param UpdateRequest $request
     * @return \think\response\Json
     */
    public function update($id, UpdateRequest $request)
    {
        $this->user->updateBy($id, $request->filterEmptyField()->param());

        $user = $this->user->findBy($id);

        $user->detachRoles();
        $user->detachJobs();

        if (!empty($request->param('roles'))) {
            $user->attachRoles($request->param('roles'));
        }
        if (!empty($request->param('jobs'))) {
            $user->attachJobs($request->param('jobs'));
        }
        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        $ids = Utils::stringToArrayBy($id);

        foreach ($ids as $_id) {
          $user = $this->user->findBy($_id);
          // 删除角色
          $user->detachRoles();
          // 删除岗位
          $user->detachJobs();

          $this->user->deleteBy($_id);
        }

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月07日
     * @param $id
     * @return \think\response\Json
     */
    public function switchStatus($id): \think\response\Json
    {
        $ids = Utils::stringToArrayBy($id);

        foreach ($ids as $_id) {

          $user = $this->user->findBy($_id);

          $this->user->updateBy($_id, [
            'status' => $user->status == Users::ENABLE ? Users::DISABLE : Users::ENABLE,
          ]);
        }

        return CatchResponse::success([], '操作成功');
    }

    /**
     *
     * @time 2019年12月07日
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function recover($id): \think\response\Json
    {
       $trashedUser = $this->user->findBy($id, ['*'], true);

       if ($this->user->where('email', $trashedUser->email)->find()) {
           return CatchResponse::fail(sprintf('该恢复用户的邮箱 [%s] 已被占用', $trashedUser->email));
       }

       return CatchResponse::success($this->user->recover($id));
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @param Roles $roles
     * @return \think\response\Json
     */
    public function getRoles(Request $request, Roles $roles): \think\response\Json
    {
        $roles = Tree::done($roles->getList());

        $roleIds = [];
        if ($request->param('uid')) {
            $userHasRoles = $this->user->findBy($request->param('uid'))->getRoles();
            foreach ($userHasRoles as $role) {
                $roleIds[] = $role->pivot->role_id;
            }
        }

        return CatchResponse::success([
            'roles' => $roles,
            'hasRoles' => $roleIds,
        ]);
    }

    /**
     * 导出
     *
     * @time 2020年09月08日
     * @param Excel $excel
     * @param UserExport $userExport
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @return \think\response\Json
     */
    public function export(Excel $excel, UserExport $userExport)
    {
        return CatchResponse::success($excel->save($userExport, Utils::publicPath('export/users')));
    }

    /**
     * 更新个人信息
     *
     * @time 2020年09月20日
     * @param ProfileRequest $request
     * @return \think\response\Json
     */
    public function profile(ProfileRequest $request)
    {
       return CatchResponse::success($this->user->updateBy($request->user()->id, $request->param()));
    }
}
