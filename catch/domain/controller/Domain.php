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

use catchAdmin\domain\support\contract\DomainActionInterface;
use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;

class Domain extends CatchController
{
    protected $domain;

    public function __construct(DomainActionInterface $domain)
    {
        $this->domain = $domain;
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
        return CatchResponse::paginate($this->domain->getList($request->param()));
    }

    /**
     * 保存
     *
     * @time 2020/09/11 18:14
     * @param Request Request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->domain->add($request->post()));
    }

    /**
     * 读取
     *
     * @time 2020/09/11 18:14
     * @param $name
     * @return \think\Response
     */
    public function read($name)
    {
        return CatchResponse::success($this->domain->info($name));
    }

    /**
     * 更新
     *
     * @time 2020/09/11 18:14
     * @param Request $request
     * @return \think\Response
     */
    public function update(Request $request, $name)
    {
        return CatchResponse::success($this->model->updateBy($id, $request->post()));
    }

    /**
     * 删除
     *
     * @time 2020/09/11 18:14
     * @param $name
     * @return \think\Response
     */
    public function delete($name)
    {
        return CatchResponse::success($this->domain->delete($name));
    }


}