<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use think\facade\Db;
use catchAdmin\system\model\LoginLog as Log;

class LoginLog extends CatchController
{
    /**
     *
     * @time 2020年04月28日
     * @param Log $log
     * @throws \think\db\exception\DbException
     * @return \think\response\Json
     */
    public function list(Log $log)
    {
        return CatchResponse::paginate($log->paginate());
    }

    /**
     * 清空
     * 
     * @time 2020年04月28日
     * @param Log $log
     * @throws \Exception
     * @return \think\response\Json
     */
    public function empty(Log $log)
    {
        return CatchResponse::success($log->where('id', '>', 0)->delete(), '清空成功');
    }
}
