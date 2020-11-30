<?php

namespace catchAdmin\config\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\config\model\FinanceConfig as financeConfigModel;

class FinanceConfig extends CatchController
{
    protected $financeConfigModel;

    public function __construct(FinanceConfigModel $financeConfigModel)
    {
        $this->financeConfigModel = $financeConfigModel;
    }

    /**
     * 列表
     * @time 2020年11月27日 06:11
     * @param Request $request
     */
    public function index(Request $request): \think\Response
    {
        return CatchResponse::paginate($this->financeConfigModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年11月27日 06:11
     * @param Request $request
     */
    public function save(Request $request): \think\Response
    {
        return CatchResponse::success($this->financeConfigModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年11月27日 06:11
     * @param $id
     */
    public function read($id): \think\Response
    {
        return CatchResponse::success($this->financeConfigModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年11月27日 06:11
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id): \think\Response
    {
        return CatchResponse::success($this->financeConfigModel->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年11月27日 06:11
     * @param $id
     */
    public function delete($id): \think\Response
    {
        return CatchResponse::success($this->financeConfigModel->deleteBy($id));
    }
}
