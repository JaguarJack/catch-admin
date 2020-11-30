<?php

namespace catchAdmin\trade\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\trade\model\TradeConfig as tradeConfigModel;

class TradeConfig extends CatchController
{
    protected $tradeConfigModel;

    public function __construct(TradeConfigModel $tradeConfigModel)
    {
        $this->tradeConfigModel = $tradeConfigModel;
    }

    /**
     * 布局
     * @time 2020年11月30日 21:06
     * @param Request $request 
     */
    public function layout(Request $request): \think\Response
    {
        return CatchResponse::success($this->tradeConfigModel->getLayout());
    }

    /**
     * 列表
     * @time 2020年11月30日 21:06
     * @param Request $request 
     */
    public function index(Request $request): \think\Response
    {
        return CatchResponse::paginate($this->tradeConfigModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年11月30日 21:06
     * @param Request $request 
     */
    public function save(Request $request): \think\Response
    {
        return CatchResponse::success($this->tradeConfigModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年11月30日 21:06
     * @param $id 
     */
    public function read($id): \think\Response
    {
        return CatchResponse::success($this->tradeConfigModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年11月30日 21:06
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id): \think\Response
    {
        return CatchResponse::success($this->tradeConfigModel->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年11月30日 21:06
     * @param $id
     */
    public function delete($id): \think\Response
    {
        return CatchResponse::success($this->tradeConfigModel->deleteBy($id));
    }
}
