<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Catch\Facade\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Permissions\Enums\MenuType;
use Modules\Permissions\Models\Permissions;

/**
 * @group 权限模块
 *
 * @subgroup 权限管理
 *
 * @subgroupDescription CatchAdmin 后台权限管理
 */
class PermissionsController extends Controller
{
    public function __construct(
        protected readonly Permissions $model
    ) {
    }

    /**
     * 权限列表
     *
     * @queryParam from string 权限来源
     * @queryParam permission_name string 权限名称
     *
     * @responseField data object[] data
     * @responseField data[].id int 权限ID
     * @responseField data[].type int 权限类型:1=目录,2=菜单,3=动作
     * @responseField data[].parent_id int 上级权限
     * @responseField data[].permission_name string 权限名称
     * @responseField data[].icon string 图标
     * @responseField data[].module string 权限模块
     * @responseField data[].permission_mark string 权限标识
     * @responseField data[].component string 菜单类型所属前端组件
     * @responseField data[].sort int 排序
     * @responseField data[].hidden int 是否隐藏
     * @responseField data[].redirect string 前端重定向
     * @responseField data[].route string 前端路由 path
     * @responseField data[].keep_alive int 是否缓存
     * @responseField data[].children object[] 子级
     * @responseField data[].actions object[] 对应的操作
     * @responseField data[].active_menu string 当前选中的菜单
     * @responseField data[].created_at string 创建时间
     * @responseField data[].updated_at string 更新时间
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        if ($request->get('from') == 'role') {
            return $this->model->setBeforeGetList(function ($query) {
                return $query->orderByDesc('sort');
            })->getList();
        }

        return $this->model->setBeforeGetList(function ($query) {
            return $query->with('actions')->whereIn('type', [MenuType::Top->value(), MenuType::Menu->value()])->orderByDesc('sort');
        })->getList();
    }

    /**
     * 新增权限
     *
     * @bodyParam type int 权限类型,1:目录,2:菜单,3:动作
     * @bodyParam parent_id int 上级权限
     * @bodyParam permission_name string required 权限名称
     * @bodyParam icon string 图标
     * @bodyParam module string required 权限模块
     * @bodyParam permission_mark string 权限标识
     * @bodyParam component string 菜单类型所属前端组件
     * @bodyParam route string 前端路由 path
     * @bodyParam hidden int 是否隐藏
     * @bodyParam redirect string 前端重定向
     * @bodyParam keepalive int 前端是否缓存
     * @bodyParam sort int 排序
     * @bodyParam active_menu string 当前激活的菜单(用于菜单类型激活高亮)
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * 查询权限
     *
     * @urlParam id int required 权限ID
     *
     * @param $id
     * @return Model|null
     */
    public function show($id): ?Model
    {
        return $this->model->firstBy($id);
    }

    /**
     * 更新权限
     *
     * @urlParam id int required 权限ID
     *
     * @bodyParam type int 权限类型,1:目录,2:菜单,3:动作
     * @bodyParam parent_id int 上级权限
     * @bodyParam permission_name string required 权限名称
     * @bodyParam icon string 图标
     * @bodyParam module string required 权限模块
     * @bodyParam permission_mark string 权限标识
     * @bodyParam component string 菜单类型所属前端组件
     * @bodyParam route string 前端路由 path
     * @bodyParam hidden int 是否隐藏
     * @bodyParam redirect string 前端重定向
     * @bodyParam keepalive int 前端是否缓存
     * @bodyParam sort int 排序
     * @bodyParam active_menu string 当前激活的菜单(用于菜单类型激活高亮)
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request): mixed
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * 删除权限
     *
     * @urlParam id int required 权限ID
     *
     * @return mixed
     */
    public function destroy($id)
    {
        if ($this->model->where($this->model->getParentIdColumn(), $id)->first()) {
            throw new FailedException('无法进行删除，请先删除子级');
        }

        Admin::clearAllCachedUsers();

        return $this->model->deleteBy($id);
    }

    /**
     * 禁用/启用 权限
     *
     * @urlParam id int required 权限ID
     *
     * @param $id
     * @return bool
     */
    public function enable($id): bool
    {
        return $this->model->toggleBy($id, 'hidden');
    }
}
