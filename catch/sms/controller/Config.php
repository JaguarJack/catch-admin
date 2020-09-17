<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\sms\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\sms\model\SmsConfig as SmsConfigModel;

class Config extends CatchController
{
    protected $model;
    
    public function __construct(SmsConfigModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * 列表
     *
     * @time 2020/09/16 17:28
     *  
     * @return \think\Response
     */
    public function index()
    {
        return CatchResponse::paginate($this->model->getList());
    }
    
    /**
     * 保存
     *
     * @time 2020/09/16 17:28
     * @param Request Request 
     * @return \think\Response
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->model->storeBy($request->param()));
    }
    
    /**
     * 读取
     *
     * @time 2020/09/16 17:28
     * @param $id 
     * @return \think\Response
     */
    public function read($id)
    {
       return CatchResponse::success($this->model->findBy($id));
    }
    
    /**
     * 更新
     *
     * @time 2020/09/16 17:28
     * @param Request $request 
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        return CatchResponse::success($this->model->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     *
     * @time 2020/09/16 17:28
     * @param $id 
     * @return \think\Response
     */
    public function delete($id)
    {
        return CatchResponse::success($this->model->deleteBy($id));
    }
    
    
}