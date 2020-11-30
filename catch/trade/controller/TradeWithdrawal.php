<?php

namespace catchAdmin\trade\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\trade\model\TradeWithdrawal as tradeWithdrawalModel;

class TradeWithdrawal extends CatchController
{
    protected $tradeWithdrawalModel;
    
    public function __construct(TradeWithdrawalModel $tradeWithdrawalModel)
    {
        $this->tradeWithdrawalModel = $tradeWithdrawalModel;
    }
    
    /**
     * 布局
     * @time 2020年11月30日 20:57
     * @param Request $request 
     */
    public function layout(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeWithdrawalModel->getLayout());
    }
    
    /**
     * 列表
     * @time 2020年11月30日 20:57
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tradeWithdrawalModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月30日 20:57
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeWithdrawalModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月30日 20:57
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tradeWithdrawalModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月30日 20:57
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tradeWithdrawalModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月30日 20:57
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tradeWithdrawalModel->deleteBy($id));
    }
}