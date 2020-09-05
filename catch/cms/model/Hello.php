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

namespace catchAdmin\cms\model;

use catcher\base\CatchModel as Model;

class Hello extends Model
{
    protected $name = 'hello';

    protected $field = [
        'id', // 
		'good', // 
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];
    
  
}