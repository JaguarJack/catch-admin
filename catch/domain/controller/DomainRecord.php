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
namespace catchAdmin\domain\controller;

use catchAdmin\domain\support\contract\DomainRecordInterface;
use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;

class DomainRecord extends CatchController
{
    protected $domainRecord;

    public function __construct(DomainRecordInterface $record)
    {
        $this->domainRecord = $record;
    }

    /**
     * 列表
     *
     * @time 2020/09/11 18:14
     * @param Request $request
     * @return \think\Response
     */
    public function index(Request $request): \think\Response
    {
        return CatchResponse::paginate($this->domainRecord->getList($request->param()));
    }

    /**
     * 保存
     *
     * @time 2020/09/11 18:14
     * @param Request Request
     * @return \think\Response
     */
    public function save(Request $request): \think\Response
    {
        return CatchResponse::success($this->domainRecord->store($request->post()));
    }

    /**
     * 读取
     *
     * @time 2020/09/11 18:14
     * @param $name
     * @return \think\Response
     */
    public function read($name): \think\Response
    {
        return CatchResponse::success($this->domainRecord->read($name));
    }

    /**
     * 更新
     *
     * @time 2020/09/11 18:14
     * @param Request $request
     * @param $id
     * @return \think\Response
     */
    public function update($id, Request $request): \think\Response
    {
        return CatchResponse::success($this->domainRecord->update($id, $request->post()));
    }

    /**
     * 删除
     *
     * @time 2020/09/11 18:14
     * @param $id
     * @return \think\Response
     */
    public function delete($id): \think\Response
    {
        return CatchResponse::success($this->domainRecord->delete($id));
    }

    /**
     * 设置状态
     *
     * @param $id
     * @param $status
     * @return \think\response\Json
     */
    public function enable($id, $status)
    {
        return CatchResponse::success($this->domainRecord->enable($id, $status));
    }
}