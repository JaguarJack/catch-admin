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

namespace catchAdmin\sms\model;

use catchAdmin\sms\model\search\SmsTemplateSearch;
use catcher\base\CatchModel as Model;

class SmsTemplate extends Model
{
    use SmsTemplateSearch;

    protected $name = 'sms_template';

    protected $field = [
        'id', //
        'operator', // 运营商
		'name', // 模版名称
		'identify', // 模版标识
		'code', // 模版CODE
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];

    protected $paginate = false;
  
}