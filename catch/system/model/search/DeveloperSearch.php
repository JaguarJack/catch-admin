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
namespace catchAdmin\system\model\search;

trait DeveloperSearch
{
    public function searchUsernameAttr($query, $value, $data)
    {
        return $query->whereLike('username', $value);
    }

    public function searchMobileAttr($query, $value, $data)
    {
        return $query->whereLike('mobile', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where('driver', $value);
    }
}