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

use catchAdmin\monitor\model\search\CrontabSearch;
use catcher\base\CatchModel as Model;

class Crontab extends Model
{
    use CrontabSearch;

    protected $name = 'crontab';

    protected $field = [
        'id', // 
		'name', // 任务名称
		'group', // 1 默认 2 系统
		'task', // 任务名称
		'cron', // cron 表达式
		'tactics', // 1 立即执行 2 执行一次 3 放弃执行
		'status', // 1 正常 2 禁用
		'remark', // 备注
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];


    const EXECUTE_IMMEDIATELY = 1; // 立即执行
    const EXECUTE_ONCE = 2; // 执行一次
    const EXECUTE_NORMAL = 3; // 正常执行
}