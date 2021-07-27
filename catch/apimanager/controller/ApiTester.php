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
use catchAdmin\apimanager\model\ApiTester as ApiTesterModel;
use catchAdmin\apimanager\model\ApiCategory;

class ApiTester extends CatchController
{
    protected $ApiTesterModel;
    
    public function __construct(ApiTesterModel $ApiTesterModel)
    {
        $this->ApiTesterModel = $ApiTesterModel;
    }
    
    /**
     * 列表
     * @time 2021年05月20日 11:41
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->ApiTesterModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2021年05月20日 11:41
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2021年05月20日 11:41
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2021年05月20日 11:41
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2021年05月20日 11:41
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterModel->deleteBy($id));
    }
}