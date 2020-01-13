<?php
namespace catchAdmin\permissions\model\search;

trait DepartmentSearch
{
    public function searchDepartmentNameAttr($query, $value, $data)
    {
        return $query->whereLike('department_name', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where('status', $value);
    }
}
