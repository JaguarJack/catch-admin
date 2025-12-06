<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Catch\Facade\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Permissions\Enums\DataRange;
use Modules\Permissions\Http\Requests\RoleRequest;
use Modules\Permissions\Models\Roles;

/**
 * @group 权限模块
 *
 * @subgroup 角色管理
 * @subgroupDescription CatchAdmin 后台角色管理
 */
class RolesController extends Controller
{
    public function __construct(
        protected readonly Roles $model
    ) {
    }

    /**
     * 角色列表
     *
     * @queryParam role_name string 角色名称
     *
     * @responseField data object[] data
     * @responseField data[].id int 角色ID
     * @responseField data[].role_name string 角色名称
     * @responseField data[].identify string 角色标识
     * @responseField data[].description string 角色描述
     * @responseField data[].data_range int 角色数据范围:1=全部数据,2=自定义数据,3=本人数据,4=部门数据,5=部门及以下数据
     * @responseField data[].permissions object[] 角色权限
     * @responseField data[].created_at string 创建时间
     * @responseField data[].updated_at string 更新时间
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->setBeforeGetList(function ($query) {
            return $query->with(['permissions' => function ($query) {
                $query->select('id');
            }]);
        })->getList();
    }

    /**
     * 新增角色
     *
     * @bodyParam parent_id int 上级角色
     * @bodyParam role_name string required 角色名称
     * @bodyParam identify string required 角色标识
     * @bodyParam description string 角色描述
     * @bodyParam data_range int 角色数据范围
     * @bodyParam permissions integer[] 角色权限 Example: [4, 6]
     *
     * @return bool
     */
    public function store(RoleRequest $request)
    {
        $data = $request->all();

        if (! isset($data['data_range'])) {
            $data['data_range'] = 0;
        } else {
            $data['data_range'] = (int) $data['data_range'];
            if (! DataRange::Personal_Choose->assert($data['data_range'])) {
                $data['departments'] = [];
            }
        }

        return $this->model->storeBy($data);
    }

    /**
     * 查询角色
     *
     * @urlParam id int required 角色ID
     *
     * @param $id
     * @param Request $request
     * @return Model|null
     */
    public function show($id, Request $request)
    {
        $role = $this->model->firstBy($id);

        if ($request->has('from') && $request->get('from') == 'parent_role') {
            $role->setAttribute('permissions', $role->permissions()->get()->toTree());
        } else {
            $role->setAttribute('permissions', $role->permissions()->pluck('id'));
        }

        $role->setAttribute('departments', $role->departments()->pluck('id'));

        return $role;
    }

    /**
     * 更新角色
     *
     * @urlParam id int required 角色ID
     *
     * @bodyParam parent_id int 上级角色
     * @bodyParam role_name string required 角色名称
     * @bodyParam identify string required 角色标识
     * @bodyParam description string 角色描述
     * @bodyParam data_range int 角色数据范围
     * @bodyParam permissions integer[] 角色权限 Example: [4, 6]
     *
     * @return bool
     */
    public function update($id, RoleRequest $request)
    {
        $data = $request->all();
        $data['data_range'] = (int) $data['data_range'];
        if (! DataRange::Personal_Choose->assert($data['data_range'])) {
            $data['departments'] = [];
        }

        Admin::clearAllCachedUsers();

        return $this->model->updateBy($id, $data);
    }

    /**
     * 删除角色
     *
     * @urlParam id int required 角色ID
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        if ($this->model->where($this->model->getParentIdColumn(), $id)->first()) {
            throw new FailedException('请先删除子角色');
        }

        Admin::clearAllCachedUsers();

        return $this->model->deleteBy($id);
    }
}
