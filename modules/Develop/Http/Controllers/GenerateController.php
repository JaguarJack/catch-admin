<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Develop\Support\Generate\Generator;

class GenerateController extends Controller
{
    /**
     * @param Request $request
     * @param Generator $generator
     * @throws Exception
     */
    public function index(Request $request, Generator $generator)
    {
        $generator->setParams($request->all())->generate();
    }
}
