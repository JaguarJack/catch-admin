<?php

namespace catchAdmin\order\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\order\model\TradeOrder as tradeOrderModel;

class TradeOrder extends CatchController
{
    protected $tradeOrderModel;
    
    public function __construct(TradeOrderModel $tradeOrderModel)
    {
        $this->tradeOrderModel = $tradeOrderModel;
    }
    
    /**
     * 布局
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function layout(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeOrderModel->getLayout());
    }
    
    /**
     * 列表
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tradeOrderModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeOrderModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月30日 21:02
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tradeOrderModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月30日 21:02
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tradeOrderModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月30日 21:02
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tradeOrderModel->deleteBy($id));
    }
}