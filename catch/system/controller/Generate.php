<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\generate\Generator;
use think\Request;

class Generate extends CatchController
{
    public function save(Request $request, Generator $generator)
    {
        return CatchResponse::success($generator->done($request->param()));
    }

    public function preview()
    {

    }
}
