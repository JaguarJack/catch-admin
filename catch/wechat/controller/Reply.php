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

use catchAdmin\wechat\repository\WechatReplyRepository;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;

class Reply extends CatchController
{
    protected $reply;

    public function __construct(WechatReplyRepository $reply)
    {
        $this->reply = $reply;
    }

    /**
     * 列表
     *
     * @time 2020年06月29日
     * @param CatchRequest $request
     * @return \think\response\Json
     */
    public function index(CatchRequest $request)
    {
        return CatchResponse::paginate($this->reply->getList());
    }

    /**
     * 读取
     *
     * @time 2020年09月13日
     * @param $id
     * @return \think\response\Json
     */
    public function read($id)
    {
       return CatchResponse::success($this->reply->findBy($id));
    }

    /**
     * 保存
     *
     * @time 2020年06月29日
     * @param CatchRequest $request
     * @return \think\response\Json
     */
    public function save(CatchRequest $request)
    {
        return CatchResponse::success($this->reply->storeBy($request->param()));
    }

    /**
     * 更新
     *
     * @time 2020年09月13日
     * @param $id
     * @param CatchRequest $request
     * @return \think\response\Json
     */
    public function update($id, CatchRequest $request)
    {
        return CatchResponse::success($this->reply->updateBy($id, $request->param()));
    }
    /**
     * 删除
     *
     * @time 2020年06月29日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->reply->deleteBy($id));
    }

    /**
     * 禁用启用
     *
     * @time 2020年06月29日
     * @param $id
     * @return \think\response\Json
     */
    public function disOrEnable($id)
    {
        return CatchResponse::success($this->reply->disOrEnable($id));
    }
}