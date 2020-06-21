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

namespace catchAdmin\system\model;

use catchAdmin\permissions\model\Users;
use catcher\base\CatchModel;

class SensitiveWord extends CatchModel
{
    protected $name = 'sensitive_word';

    protected $field = [
        'id', // 
		'word', // 词汇
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 删除时间
    ];

    /**
     * 词汇查询
     *
     * @time 2020年06月17日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchWordAttr($query, $value, $data)
    {
        return $query->whereLike('word', $value);
    }
}