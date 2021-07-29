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


use catcher\base\CatchController;
use catchAdmin\apimanager\model\ApiCategory as ApiCategoryModel;
use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;

class ApiCategory extends CatchController
{
    protected $ApiCategoryModel;
    
    public function __construct(ApiCategoryModel $ApiCategoryModel)
    {
        $this->ApiCategoryModel = $ApiCategoryModel;
    }
    
    /**
     * 列表
     * @time 2021年05月19日 15:21
     * @param Request $request 
     */
    public function index(): \think\response\Json
    {
        return CatchResponse::success($this->ApiCategoryModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2021年05月19日 15:21
     * @param Request $request 
     */
    public function save(Request $request) : \think\response\Json
    {
        return CatchResponse::success($this->ApiCategoryModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2021年05月19日 15:21
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->ApiCategoryModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2021年05月19日 15:21
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\response\Json
    {
        return CatchResponse::success($this->ApiCategoryModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2021年05月19日 15:21
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        if ($this->ApiCategoryModel->where('parent_id', $id)->find()) {
            throw new FailedException('存在子分类，无法删除');
        }
        return CatchResponse::success($this->ApiCategoryModel->deleteBy($id));
    }
}