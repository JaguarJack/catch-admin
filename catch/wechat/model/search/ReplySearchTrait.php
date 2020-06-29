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

trait ReplySearchTrait
{
    /**
     * 规则查询
     *
     * @time 2020年06月30日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchRuleTypeAttr($query, $value, $data)
    {
        return $query->where('rule_type', $value);
    }

    /**
     * 类型查询
     *
     * @time 2020年06月30日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchTypeAttr($query, $value, $data)
    {
        return $query->where('type', $value);
    }

    /**
     * 状态查询
     *
     * @time 2020年06月30日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where('status', $value);
    }
}