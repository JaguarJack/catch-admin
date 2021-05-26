<?php
namespace catchAdmin\cms\model\search;

trait UsersSearch
{
    public function searchUsernameAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('name'), $value);
    }

    public function searchEmailAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('email'), $value);
    }

    public function searchMobileAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('mobile'), $value);
    }

    public function searchStatusAttr($query, $value)
    {
        return $query->where($this->aliasField('status'), $value);
    }
}