<?php
declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\System\Models\Dictionary;
use Illuminate\Http\Request;


class DictionaryController extends Controller
{
    public function __construct(
        protected readonly Dictionary $model
    ){}

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $dictionary = $this->model->find($id);

        if ($this->model->deleteBy($id)) {
            return $dictionary->values()->delete();
        }

        return false;
    }

    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }
}
