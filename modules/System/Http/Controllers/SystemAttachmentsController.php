<?php

declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Http\Request;
use Modules\System\Models\SystemAttachments;

class SystemAttachmentsController extends Controller
{
    public function __construct(
        protected readonly SystemAttachments $model
    ) {
    }

    public function index(): mixed
    {
        return $this->model->getList();
    }

    public function store(Request $request): mixed
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->deletesBy($id);
    }
}
