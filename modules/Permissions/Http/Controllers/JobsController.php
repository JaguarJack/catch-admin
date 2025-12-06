<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Http\Request;
use Modules\Permissions\Models\Jobs;

/**
 * @group 权限模块
 *
 * @subgroup 岗位管理
 * @subgroupDescription CatchAdmin 后台岗位管理
 */
class JobsController extends Controller
{
    public function __construct(
        protected readonly Jobs $model
    ) {
    }

    /**
     * 职位列表
     *
     * @queryParam job_name string 岗位名称
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField page int 当前页
     * @responseField total int 总数
     * @responseField limit int 每页数量
     * @responseField data object[] data
     * @responseField data[].id int 岗位ID
     * @responseField data[].job_name string 岗位名称
     * @responseField data[].coding string 岗位编码
     * @responseField data[].sort int 排序
     * @responseField data[].description string 岗位描述
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
     * 新增岗位
     *
     * @bodyParam job_name string required 岗位名称
     * @bodyParam coding string 岗位编码
     * @bodyParam sort int 排序
     * @bodyParam description string 岗位描述
     * @bodyParam status int 状态
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * 查询岗位
     *
     * @urlParam id int required 岗位ID
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
     * 更新岗位
     *
     * @bodyParam job_name string required 岗位名称
     * @bodyParam coding string 岗位编码
     * @bodyParam sort int 排序
     * @bodyParam description string 岗位描述
     * @bodyParam status int 状态
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
     * 删除岗位
     *
     * @urlParam id int required 岗位ID
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }

    /**
     * 开启/禁用岗位
     *
     * @urlParam id int required 岗位ID
     *
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }
}
