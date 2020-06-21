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
namespace catchAdmin\wechat\model\search;

trait TagSearchTrait
{
    /**
     * 昵称搜索
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchNameAttr($query, $value, $data)
    {
        return $query->whereLike('name', $value);
    }
}