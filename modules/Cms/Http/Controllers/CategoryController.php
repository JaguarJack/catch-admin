<?php
declare(strict_types=1);

namespace Modules\Cms\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\Cms\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function __construct(
        protected readonly Category $model
    ){}

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * store
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * show
     *
     * @param $id
     * @return mixed
     */
    public function show($id): mixed
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
     * @return bool|null
     */
    public function destroy($id): ?bool
    {
        return $this->model->deleteBy($id);
    }
}
