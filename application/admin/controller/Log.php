<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 9:01
 */
namespace app\admin\controller;

use app\model\LogRecordModel;

class Log extends Base
{
    /**
     * 日志列表
     *
     * @time at 2019年01月18日
     * @param LogRecordModel $logRecordModel
     * @return mixed
     */
    public function index(LogRecordModel $logRecordModel)
    {
        $params = $this->request->param();
        $this->checkParams($params);

        $this->list = $logRecordModel->getAll($params, $this->limit);

        return $this->fetch();
    }
}