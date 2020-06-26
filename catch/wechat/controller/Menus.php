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
namespace catchAdmin\wechat\controller;

use catchAdmin\wechat\repository\WechatMenusRepository;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;

class Menus extends CatchController
{
    protected $menus;

    public function __construct(WechatMenusRepository $repository)
    {
        $this->menus = $repository;
    }

    public function index()
    {
        return CatchResponse::success($this->menus->all());
    }

    /**
     * 保存
     *
     * @time 2020年06月26日
     * @param CatchRequest $request
     * @return \think\response\Json
     */
    public function save(CatchRequest $request)
    {
        return CatchResponse::success($this->menus->storeBy($request->param()));
    }

    /**
     * 更新
     *
     * @time 2020年06月26日
     * @param $id
     * @param CatchRequest $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\response\Json
     */
    public function update($id, CatchRequest $request)
    {
        return CatchResponse::success($this->menus->updateBy($id, $request->param()));
    }

    /**
     * 删除
     *
     * @time 2020年06月26日
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->menus->deleteBy($id));
    }

    /**
     * 同步
     *
     * @time 2020年06月26日
     * @return \think\response\Json
     * @throws \Exception
     */
    public function sync()
    {
        return CatchResponse::success($this->menus->sync());
    }
}