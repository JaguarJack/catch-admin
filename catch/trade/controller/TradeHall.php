<?php

namespace catchAdmin\trade\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\trade\model\TradeHall as tradeHallModel;

class TradeHall extends CatchController
{
    protected $tradeHallModel;
    
    public function __construct(TradeHallModel $tradeHallModel)
    {
        $this->tradeHallModel = $tradeHallModel;
    }
    
    /**
     * 布局
     * @time 2020年11月30日 15:25
     * @param Request $request 
     */
    public function layout(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeHallModel->getLayout());
    }
    
    /**
     * 列表
     * @time 2020年11月30日 15:25
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tradeHallModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月30日 15:25
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tradeHallModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月30日 15:25
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tradeHallModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月30日 15:25
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tradeHallModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月30日 15:25
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tradeHallModel->deleteBy($id));
    }
}