<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Facade\Admin;
use Illuminate\Http\Request;
use Modules\Permissions\Models\Departments;

/**
 * @group 权限模块
 *
 * @subgroup 部门管理
 * @subgroupDescription CatchAdmin 后台部门管理
 */
class DepartmentsController extends Controller
{
    public function __construct(
        protected readonly Departments $model
    ) {
    }

    /**
     * 部门列表
     *
     * @queryParam department_name string 部门名称
     *
     * @responseField data object[] data
     * @responseField data.id int 部门ID
     * @responseField data[].parent_id int 上级部门
     * @responseField data[].department_name string 部门名称
     * @responseField data[].sort int 排序
     * @responseField data[].status int 状态
     * @responseField data[].created_at string 创建时间
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * 新增部门
     *
     * @bodyParam parent_id int 上级部门
     * @bodyParam department_name string required 部门名称
     * @bodyParam principal string 负责人
     * @bodyParam mobile string 负责人手机号
     * @bodyParam email string 负责人邮箱
     * @bodyParam sort int 排序
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * 查询部门
     *
     * @urlParam id int required 部门ID
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
     * 更新部门
     *
     * @urlParam id int required 部门ID
     *
     * @bodyParam parent_id int 上级部门
     * @bodyParam department_name string required 部门名称
     * @bodyParam principal string 负责人
     * @bodyParam mobile string 负责人手机号
     * @bodyParam email string 负责人邮箱
     * @bodyParam sort int 排序
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * 删除部门
     *
     * @urlParam id int required 部门ID
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        Admin::clearAllCachedUsers();

        return $this->model->deleteBy($id);
    }

    /**
     * 禁用/启用部门
     *
     * @urlParam id int required 部门ID
     *
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }
}
