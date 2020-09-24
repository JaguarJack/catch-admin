<?php

namespace catchAdmin\system\model\search;

trait LoginLogSearch
{
    public function searchStartAtAttr($query, $value, $data)
    {
        return $query->whereTime('login_at', '>=', strtotime($value));
    }

    public function searchEndAtAttr($query, $value, $data)
    {
        return $query->whereTime('login_at', '<=', strtotime($value));
    }
}
