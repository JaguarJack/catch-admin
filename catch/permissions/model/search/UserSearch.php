<?php
namespace catchAdmin\permissions\model\search;

trait UserSearch
{
    public function searchUsernameAttr($query, $value, $data)
    {
        return $query->whereLike('username', $value);
    }

    public function searchEmailAttr($query, $value, $data)
    {
        return $query->whereLike('email', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('status'), $value);
    }

    public function searchDepartmentIdAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('department_id'), $value);
    }
}
