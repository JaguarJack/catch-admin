<?php

namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
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
        return CatchResponse::paginate($log->getList());
    }

    /**
     * 清空
     * 
     * @time 2020年04月28日
     * @param Log $log
     * @param $id
     * @throws \Exception
     * @return \think\response\Json
     */
    public function empty($id, Log $log)
    {
        return CatchResponse::success($log->deleteBy($id), '删除成功');
    }
}
