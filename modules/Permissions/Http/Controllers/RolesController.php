<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Modules\Permissions\Models\Roles;
use Modules\Permissions\Http\Requests\RoleRequest;

class RolesController extends Controller
{
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
            }])->dataRange();
        })->getList();
    }

    public function store(RoleRequest $request)
    {
        return $this->model->storeBy($request->all());
    }

    public function show($id)
    {
        $role = $this->model->firstBy($id);

        $role->setAttribute('permissions', $role->permissions()->get()->toTree());

        return $role;
    }

    public function update($id, RoleRequest $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    public function destroy($id)
    {
        if ($this->model->where($this->model->getParentIdColumn(), $id)->first()) {
            throw new FailedException('请先删除子级');
        }

        return $this->model->deleteBy($id);
    }
}
