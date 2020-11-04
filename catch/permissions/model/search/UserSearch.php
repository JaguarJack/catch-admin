<?php
namespace catchAdmin\permissions\model\search;

use catchAdmin\permissions\model\Department;

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

    /**
     * 查询部门下的用户
     *
     * @time 2020年11月04日
     * @param $query
     * @param $value
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return mixed
     */
    public function searchDepartmentIdAttr($query, $value, $data)
    {
        return $query->whereIn($this->aliasField('department_id'), Department::getChildrenDepartmentIds($value));
    }
}
