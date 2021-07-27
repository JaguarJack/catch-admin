<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

namespace catchAdmin\apimanager\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\apimanager\model\ApiTesterLog as ApiTesterLogModel;

class ApiTesterLog extends CatchController
{
    protected $ApiTesterLogModel;
    
    public function __construct(ApiTesterLogModel $ApiTesterLogModel)
    {
        $this->ApiTesterLogModel = $ApiTesterLogModel;
    }
    
    /**
     * 列表
     * @time 2021年06月10日 19:20
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->ApiTesterLogModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2021年06月10日 19:20
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterLogModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2021年06月10日 19:20
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterLogModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2021年06月10日 19:20
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterLogModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2021年06月10日 19:20
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterLogModel->deleteBy($id));
    }
}