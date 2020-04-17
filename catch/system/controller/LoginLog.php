<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use think\facade\Db;

class LoginLog extends CatchController
{
    public function list()
    {
        return CatchResponse::paginate(Db::name('login_log')->paginate(request()->param('limit') ?? 10));
    }

    public function empty()
    {
        return CatchResponse::success(Db::name('login_log')->delete(true), '清空成功');
    }
}
