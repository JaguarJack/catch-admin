<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
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
        $dataRange = $request->get('data_range');

        if ($dataRange && ! DataRange::Personal_Choose->assert($request->get('data_range'))) {
            $request['departments'] = [];
        }

        return $this->model->storeBy($request->all());
    }

    /**
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        $role = $this->model->firstBy($id);

        $role->setAttribute('permissions', $role->permissions()->get()->toTree());

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
        $dataRange = $request->get('data_range');

        if ($dataRange && ! DataRange::Personal_Choose->assert($request->get('data_range'))) {
            $request['departments'] = [];
        }

        return $this->model->updateBy($id, $request->all());
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
