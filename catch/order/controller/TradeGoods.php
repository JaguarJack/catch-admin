<?php

namespace catchAdmin\order\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\order\model\TradeGoods as tradeGoodsModel;

class TradeGoods extends CatchController
{
    protected $tradeGoodsModel;
    
    public function __construct(TradeGoodsModel $tradeGoodsModel)
    {
        $this->tradeGoodsModel = $tradeGoodsModel;
    }
    
    /**
     * 布局
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function layout(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeGoodsModel->getLayout());
    }
    
    /**
     * 列表
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tradeGoodsModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月30日 21:02
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeGoodsModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月30日 21:02
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tradeGoodsModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月30日 21:02
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tradeGoodsModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月30日 21:02
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tradeGoodsModel->deleteBy($id));
    }
}