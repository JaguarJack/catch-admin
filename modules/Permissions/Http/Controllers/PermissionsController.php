<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Database\Eloquent\Model;
use Modules\Permissions\Enums\MenuType;
use Modules\Permissions\Models\Permissions;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * @param Permissions $model
     */
    public function __construct(
        protected readonly Permissions $model
    ) {
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        if ($request->get('from') == 'role') {
            return $this->model->setBeforeGetList(function ($query){
                return $query->orderByDesc('sort');
            })->getList();
        }

        return $this->model->setBeforeGetList(function ($query) {
            return $query->with('actions')->whereIn('type', [MenuType::Top->value(), MenuType::Menu->value()])->orderByDesc('sort');
        })->getList();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     * @throws \ReflectionException
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     *
     * @param $id
     * @return Model|null
     */
    public function show($id): ?Model
    {
        return $this->model->firstBy($id);
    }

    /**
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
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        if ($this->model->where($this->model->getParentIdColumn(), $id)->first()) {
            throw new FailedException('无法进行删除，请先删除子级');
        }

        return $this->model->deleteBy($id);
    }

    /**
     * enable
     *
     * @param $id
     * @return bool
     */
    public function enable($id): bool
    {
        return $this->model->toggleBy($id, 'hidden');
    }
}
