<?php

namespace catchAdmin\cms\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\cms\model\Tags as tagsModel;

class Tags extends CatchController
{
    protected $tagsModel;
    
    public function __construct(TagsModel $tagsModel)
    {
        $this->tagsModel = $tagsModel;
    }
    
    /**
     * 列表
     * @time 2020年12月27日 19:44
     * @param Request $request 
     */
    public function index(Request $request) : \think\Response
    {
        return CatchResponse::paginate($this->tagsModel->getList());
    }
    
    /**
     * 保存信息
     * @time 2020年12月27日 19:44
     * @param Request $request 
     */
    public function save(Request $request) : \think\Response
    {
        return CatchResponse::success($this->tagsModel->storeBy($request->post()));
    }
    
    /**
     * 读取
     * @time 2020年12月27日 19:44
     * @param $id 
     */
    public function read($id) : \think\Response
    {
        return CatchResponse::success($this->tagsModel->findBy($id));
    }
    
    /**
     * 更新
     * @time 2020年12月27日 19:44
     * @param Request $request 
     * @param $id
     */
    public function update(Request $request, $id) : \think\Response
    {
        return CatchResponse::success($this->tagsModel->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     * @time 2020年12月27日 19:44
     * @param $id
     */
    public function delete($id) : \think\Response
    {
        return CatchResponse::success($this->tagsModel->deleteBy($id));
    }
}