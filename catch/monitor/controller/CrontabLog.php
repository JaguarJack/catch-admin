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

use catchAdmin\monitor\model\CrontabLog as LogModel;
use catcher\base\CatchController;
use catcher\CatchResponse;
use think\Request;

class CrontabLog extends CatchController
{
    protected $model;

    public function __construct(LogModel $model)
    {
        $this->model = $model;
    }

    /**
     * 日志列表
     *
     * @time 2020年09月15日
     * @throws \think\db\exception\DbException
     * @return \think\response\Json
     */
    public function index()
    {
        return CatchResponse::paginate($this->model->getList());
    }

    /**
     * 删除日志
     *
     * @time 2020年09月15日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->model->deleteBy($id));
    }
}