<?php

declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\System\Models\SystemSmsCode;

class SystemSmsCodeController extends Controller
{
    public function __construct(
        protected readonly SystemSmsCode $model
    ) {
    }

    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }
}
