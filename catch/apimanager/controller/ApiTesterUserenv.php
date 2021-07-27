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
use catchAdmin\apimanager\model\ApiTesterUserenv as ApiTesterUserenvModel;
use think\facade\Log;

class ApiTesterUserenv extends CatchController
{
    protected $ApiTesterUserenvModel;
    
    public function __construct(ApiTesterUserenvModel $ApiTesterUserenvModel)
    {
        $this->ApiTesterUserenvModel = $ApiTesterUserenvModel;
    }
    
    /**
     * 列表
     * @time 2021年05月26日 18:28
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->ApiTesterUserenvModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2021年05月26日 18:28
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterUserenvModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2021年05月26日 18:28
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterUserenvModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2021年05月26日 18:28
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterUserenvModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2021年05月26日 18:28
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->ApiTesterUserenvModel->deleteBy($id));
    }

    /**
     * 切换API环境
     * @param Request $request
     * @param $id
     */
    public function selectAPIenv(Request $request,$id = "") : \think\Response
    {
        if ($id)
        {
            $creator_id = $request->user()->id;
            $this->ApiTesterUserenvModel->update(['selected' => 0], ['creator_id' => $creator_id]);  //全不选
            $res = $this->ApiTesterUserenvModel->update(['selected' => 1], ['id' => $id]);  //选中当前
            //设置为管理员当前选中的applet
            if($res){
                return CatchResponse::success($res,'切换API环境成功');
            }
        }
        return CatchResponse::fail('切换API环境错误');
    }
}