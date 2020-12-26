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
namespace catchAdmin\monitor\model\search;

trait CrontabSearch
{
    public function searchNameAttr($query, $value, $data)
    {
        return $query->whereLike('name', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where('status', $value);
    }
}
