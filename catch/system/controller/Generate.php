<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\generate\CreateModule;
use catcher\generate\Generator;
use think\Request;

class Generate extends CatchController
{
    public function save(Request $request, Generator $generator)
    {
        return CatchResponse::success($generator->done($request->param()));
    }

    /**
     * 预览
     *
     * @time 2020年04月29日
     * @param Request $request
     * @param Generator $generator
     * @return \think\response\Json
     */
    public function preview(Request $request, Generator $generator)
    {
        return CatchResponse::success($generator->preview($request->param()));
    }


    public function createModule(Request $request, CreateModule $module)
    {
        return CatchResponse::success($module->generate($request->post()));
    }
}
