<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use think\facade\Db;

class OperateLog extends CatchController
{
    public function list()
    {
        return CatchResponse::paginate(
            Db::name('operate_log')
                ->field(['operate_log.*', 'users.username as creator'])
                ->join('users','users.id = operate_log.creator_id')
                ->order('id', 'desc')
                ->paginate(10)
        );
    }

    public function empty()
    {
        return CatchResponse::success(Db::name('operate_log')->delete(true), '清空成功');
    }
}
