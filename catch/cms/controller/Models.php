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
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\cms\model\Models as CmsModel;

class Models extends CatchController
{
    protected $cmsModel;
    
    public function __construct(CmsModel $cmsModel)
    {
        $this->cmsModel = $cmsModel;
    }

    /**
     * 列表
     * @time 2020年12月29日 20:02
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request): \think\response\Json
    {
        return CatchResponse::paginate($this->cmsModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年12月29日 20:02
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request): \think\response\Json
    {
        return CatchResponse::success($this->cmsModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年12月29日 20:02
     * @param $id
     * @return \think\response\Json
     */
    public function read($id): \think\response\Json
    {
        return CatchResponse::success($this->cmsModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年12月29日 20:02
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function update(Request $request, $id): \think\response\Json
    {
        return CatchResponse::success($this->cmsModel->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年12月29日 20:02
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id): \think\response\Json
    {
        return CatchResponse::success($this->cmsModel->deleteBy($id));
    }
}