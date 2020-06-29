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

    public function index(CatchRequest $request)
    {
        return CatchResponse::paginate($this->reply->getList());
    }

    public function save(CatchRequest $request)
    {
        return CatchResponse::success($this->reply->storeBy($request->param()));
    }

    public function update($id, CatchRequest $request)
    {
        return CatchResponse::success($this->reply->updateBy($id, $request->param()));
    }

    public function delete($id)
    {
        return CatchResponse::success($this->reply->deleteBy($id));
    }
}