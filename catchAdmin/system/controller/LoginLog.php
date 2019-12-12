<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use think\facade\Db;

class LoginLog extends CatchController
{
    public function index()
    {
        return $this->fetch();
    }

    public function list()
    {
        return CatchResponse::paginate(Db::name('login_log')->paginate(10));
    }
}
