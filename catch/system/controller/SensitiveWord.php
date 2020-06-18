<?php
namespace catchAdmin\system\controller;

use catchAdmin\system\request\sensitiveWord\CreateRequest;
use catchAdmin\system\request\sensitiveWord\UpdateRequest;
use catcher\base\CatchController;
use catchAdmin\system\model\SensitiveWord as Model;
use catcher\base\CatchRequest;
use catcher\CatchResponse;

class SensitiveWord extends CatchController
{
    protected $sensitiveWord;

    public function __construct(Model $model)
    {
        $this->sensitiveWord = $model;
    }

    /**
     * 列表
     *
     * @time 2020年06月17日
     * @return \think\response\Json
     */
    public function index()
    {
        return CatchResponse::paginate($this->sensitiveWord->getList());
    }

    /**
     * 保存
     *
     * @time 2020年06月17日
     * @param CreateRequest $request
     * @return \think\response\Json
     */
    public function save(CreateRequest $request)
    {
        return CatchResponse::success($this->sensitiveWord->storeBy($request->param()));
    }

    /**
     * 更新
     *
     * @time 2020年06月17日
     * @param $id
     * @param UpdateRequest $request
     * @return \think\response\Json
     */
    public function update($id, UpdateRequest $request)
    {
        return CatchResponse::success($this->sensitiveWord->updateBy($id, $request->param()));

    }

    /**
     * 删除
     *
     * @time 2020年06月17日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->sensitiveWord->deleteBy($id));
    }

}
