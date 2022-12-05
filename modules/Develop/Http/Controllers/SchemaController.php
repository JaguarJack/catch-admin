<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController;
use Illuminate\Http\Request;
use Modules\Develop\Models\Schemas;

/**
 * SchemaController
 */
class SchemaController extends CatchController
{
    public function __construct(
        protected Schemas $schemas
    ) {
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->schemas->getList();
    }

    /**
     * store
     *
     * @param Request $request
     * @throws \Exception
     * @return bool
     */
    public function store(Request $request)
    {
        return $this->schemas->storeBy($request->all());
    }

    /**
     * show
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->schemas->show($id);
    }


    /**
     * destroy
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->schemas->deleteBy($id);
    }
}
