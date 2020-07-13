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

namespace catchAdmin\system\controller;

use think\Request as Request;
use catcher\CatchAuth;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\system\model\Developers as DevelopersModel;

class Developer extends CatchController
{
    protected $model;
    
    public function __construct(DevelopersModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * 列表
     *
     * @time 2020/07/13 15:26
     *  
     * @return \think\Response
     */
    public function index()
    {
        return CatchResponse::paginate($this->model->getList());
    }

    /**
     * 开发者认证
     *
     * @time 2020年07月13日
     * @param Request $request
     * @param CatchAuth $auth
     * @return mixed
     */
    public function authenticate(Request $request, CatchAuth $auth)
    {
        return CatchResponse::success($auth->guard('developer')->username('username')->attempt($request->post()));
    }

    /**
     * 保存
     *
     * @time 2020/07/13 15:26
     * @param Request Request 
     * @return \think\Response
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->model->storeBy($request->post()));
    }
    
    /**
     * 读取
     *
     * @time 2020/07/13 15:26
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
     * @time 2020/07/13 15:26
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
     * @time 2020/07/13 15:26
     * @param $id 
     * @return \think\Response
     */
    public function delete($id)
    {
        return CatchResponse::success($this->model->deleteBy($id));
    }
    
    
}