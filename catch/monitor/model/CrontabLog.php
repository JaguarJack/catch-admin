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

namespace catchAdmin\monitor\model;

use catchAdmin\monitor\model\search\CrontabLogSearch;
use catcher\base\CatchModel;

class CrontabLog extends CatchModel
{
    use CrontabLogSearch;

    protected $name = 'crontab_log';

    protected $field = [
        'id', // 
		'crontab_id', // crontab 任务ID
		'used_time', // 任务消耗时间
		'status', // 1 成功 2 失败
		'error_message', // 错误信息
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];


    const SUCCESS = 1;
    const FAILED = 2;

    /**
     * 获取日志列表
     *
     * @time 2020年09月15日
     * @throws \think\db\exception\DbException
     * @return mixed|\think\Paginator
     */
    public function getList()
    {
        return $this->catchLeftJoin(Crontab::class, 'id', 'crontab_id', ['name', 'group', 'task'])
                    ->catchSearch()
                    ->catchOrder()
                    ->field(['used_time', 'error_message', 'crontab_log.status', 'crontab_log.id', 'crontab_log.created_at'])
                    ->paginate();
    }
}