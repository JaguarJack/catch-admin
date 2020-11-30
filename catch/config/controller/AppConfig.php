<?php

namespace catchAdmin\config\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\config\model\AppConfig as appConfigModel;

class AppConfig extends CatchController
{
    protected $appConfigModel;
    
    public function __construct(AppConfigModel $appConfigModel)
    {
        $this->appConfigModel = $appConfigModel;
    }
    
    /**
     * 列表
     * @time 2020年11月27日 06:11
     * @param Request $request
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->appConfigModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年11月27日 06:11
     * @param Request $request
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->appConfigModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年11月27日 06:11
     * @param $id
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->appConfigModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年11月27日 06:11
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->appConfigModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年11月27日 06:11
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->appConfigModel->deleteBy($id));
    }
}
