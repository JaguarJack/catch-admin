<?php

declare(strict_types=1);

namespace Modules\Permissions\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\Permissions\Models\Jobs;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function __construct(
        protected readonly Jobs $model
    ) {
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        return $this->model->getList();
    }

    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    public function update($id, Request $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }

    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }
}
