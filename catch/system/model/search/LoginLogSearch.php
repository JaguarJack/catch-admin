<?php

namespace catchAdmin\system\model\search;

trait LoginLogSearch
{
    public function searchLoginNameAttr($query, $value, $data)
    {
        return $query->whereLike('login_name', $value);
    }

    public function searchLoginIpAttr($query, $value, $data)
    {
        return $query->whereLike('login_ip', $value);
    }

    public function searchLoginAtAttr($query, $value, $data)
    {
        return $query->whereTime('login_at', 'between', $value);
    }
}
