<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchAdmin;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\cms\model\Category as categoryModel;
use catcher\CatchUpload;
use catcher\library\excel\reader\Reader;
use think\Exception;
use think\facade\Db;

class Category extends CatchController
{
    protected $categoryModel;
    
    public function __construct(CategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    /**
     * 列表
     * @time 2020年12月27日 19:15
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request)
    {
        return CatchResponse::success($this->categoryModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年12月27日 19:15
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->categoryModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年12月27日 19:15
     * @param $id
     * @return \think\response\Json
     */
    public function read($id)
    {
        return CatchResponse::success($this->categoryModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年12月27日 19:15
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function update(Request $request, $id)
    {
        return CatchResponse::success($this->categoryModel->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年12月27日 19:15
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->categoryModel->deleteBy($id));
    }

}