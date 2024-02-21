<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Permissions\Enums\DataRange;
use Modules\Permissions\Models\Roles;
use Modules\Permissions\Http\Requests\RoleRequest;

class RolesController extends Controller
{
    /**
     * @param Roles $model
     */
    public function __construct(
        protected readonly Roles $model
    ) {
    }

    /**
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
     *
     * @param RoleRequest $request
     * @return bool
     */
    public function store(RoleRequest $request)
    {
        $data = $request->all();
        if (!isset($data['data_range'])) {
            $data['data_range'] = 0;
        } else {
            $data['data_range'] = (int)$data['data_range'];
            if (!DataRange::Personal_Choose->assert($data['data_range'])) {
                $data['departments'] = [];
            }
        }

        return $this->model->storeBy($data);
    }

    /**
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
            $role->setAttribute('permissions', $role->permissions()->get()->pluck('id'));
        }

        $role->setAttribute('departments', $role->departments()->pluck('id'));

        return $role;
    }

    /**
     *
     * @param $id
     * @param RoleRequest $request
     * @return mixed
     */
    public function update($id, RoleRequest $request)
    {
        $data = $request->all();
        $data['data_range'] = (int) $data['data_range'];
        if (!DataRange::Personal_Choose->assert($data['data_range'])) {
            $data['departments'] = [];
        }

        return $this->model->updateBy($id, $data);
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        if ($this->model->where($this->model->getParentIdColumn(), $id)->first()) {
            throw new FailedException('请先删除子角色');
        }

        return $this->model->deleteBy($id);
    }
}
