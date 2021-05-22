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

use catchAdmin\cms\support\Table;
use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\cms\model\ModelFields as ModelFieldsModel;
use catchAdmin\cms\model\Models;
use catcher\exceptions\FailedException;

class ModelFields extends CatchController
{
    protected $modelFields;
    
    public function __construct(ModelFieldsModel $modelFields)
    {
        $this->modelFields = $modelFields;
    }

    /**
     * 列表
     * @time 2020年12月29日 21:00
     * @param Request $request
     * @param Models $models
     * @return \think\response\Json
     */
    public function index(Request $request): \think\response\Json
    {
        // $columns = Table::columns($models::where('id',$request->param('model_id'))->value('table_name'));

        //foreach ($columns as &$column) {
          //  $column['title'] = $column['comment'];
       // }

        return CatchResponse::success($this->modelFields->getFieldsByModelId($request->param('model_id')));
    }

    /**
     * 保存信息
     * @time 2020年12月29日 21:00
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request): \think\response\Json
    {
        return CatchResponse::success($this->modelFields->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年12月29日 21:00
     * @param $id
     * @return \think\response\Json
     */
    public function read($id): \think\response\Json
    {
        return CatchResponse::success($this->modelFields->findBy($id));
    }

    /**
     * 更新
     * @time 2020年12月29日 21:00
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function update(Request $request, $id): \think\response\Json
    {
        return CatchResponse::success($this->modelFields->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年12月29日 21:00
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id): \think\response\Json
    {
        return CatchResponse::success($this->modelFields->deleteBy($id));
    }
}