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

trait CrontabLogSearch
{
    public function searchCrontabIdAttr($query, $value, $data)
    {
        return $query->where('crontab_id', $value);
    }

    public function searchNameAttr($query, $value, $data)
    {
        return $query->whereLike('crontab.name', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where('crontab_log.status', $value);
    }

    public function searchStartAtAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('created_at'), '>=', strtotime($value));
    }

    public function searchEndAtAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('created_at'), '<=', strtotime($value));
    }
}
