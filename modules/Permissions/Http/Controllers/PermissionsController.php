<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Support\Facades\Route;
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
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->setBeforeGetList(function ($query) {
            return $query->with('actions')->whereIn('type', [MenuType::Top->value(), MenuType::Menu->value()]);
        })->getList();
    }

    /**
     *
     * @param Request $request
     * @return bool
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
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
     *
     * @param $id
     * @return bool|null
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
    public function enable($id)
    {
        return $this->model->toggleBy($id, 'hidden');
    }
}
