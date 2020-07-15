<?php

namespace catchAdmin\system\model\search;

trait OperateLogSearch
{
    public function searchModuleAttr($query, $value, $data)
    {
        return $query->whereLike('module', $value);
    }

    public function searchMethodAttr($query, $value, $data)
    {
        return $query->whereLike('method', $value);
    }

    public function searchCreatorAttr($query, $value, $data)
    {
        return $query->where('username', $value);
    }

    public function searchCreateAtAttr($query, $value, $data)
    {
        return $query->whereTime($this->aliasField('created_at'), 'between', $value);
    }
}
