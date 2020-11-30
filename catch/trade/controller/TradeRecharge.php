<?php

namespace catchAdmin\trade\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\trade\model\TradeRecharge as tradeRechargeModel;

class TradeRecharge extends CatchController
{
    protected $tradeRechargeModel;
    
    public function __construct(TradeRechargeModel $tradeRechargeModel)
    {
        $this->tradeRechargeModel = $tradeRechargeModel;
    }
    
    /**
     * 布局
     * @time 2020年11月30日 20:56
     * @param Request $request 
     */
    public function layout(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeRechargeModel->getLayout());
    }
    
    /**
     * 列表
     * @time 2020年11月30日 20:56
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tradeRechargeModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月30日 20:56
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeRechargeModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月30日 20:56
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tradeRechargeModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月30日 20:56
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tradeRechargeModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月30日 20:56
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tradeRechargeModel->deleteBy($id));
    }
}