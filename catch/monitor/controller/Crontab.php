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

namespace catchAdmin\monitor\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\monitor\model\Crontab as CrontabModel;
use Cron\CronExpression;

class Crontab extends CatchController
{
    protected $model;
    
    public function __construct(CrontabModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * 列表
     *
     * @time 2020/09/14 20:35
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
     * @time 2020/09/14 20:35
     * @param Request Request 
     * @return \think\Response
     */
    public function save(Request $request)
    {
        new CronExpression($request->post('cron'));

        return CatchResponse::success($this->model->storeBy($request->post()));
    }
    
    /**
     * 读取
     *
     * @time 2020/09/14 20:35
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
     * @time 2020/09/14 20:35
     * @param Request $request 
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        new CronExpression($request->post('cron'));

        return CatchResponse::success($this->model->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     *
     * @time 2020/09/14 20:35
     * @param $id 
     * @return \think\Response
     */
    public function delete($id)
    {
        return CatchResponse::success($this->model->deleteBy($id));
    }

    /**
     * 禁用启用
     *
     * @time 2020年09月15日
     * @param $id
     * @return \think\response\Json
     */
    public function disOrEnable($id)
    {
        return CatchResponse::success($this->model->disOrEnable($id));
    }
    
}