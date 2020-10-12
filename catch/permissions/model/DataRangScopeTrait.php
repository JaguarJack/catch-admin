<?php
namespace catchAdmin\permissions\model;

use catcher\Utils;

trait DataRangScopeTrait
{
    /**
     * 数据范围查询
     *
     * @param $roles
     * @return mixed
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/6/6
     */
    public function dataRange($roles = [])
    {
        if (Utils::isSuperAdmin()) {
            return $this;
        }

        $userIds =  $this->getDepartmentUserIdsBy($roles);

        if (empty($userIds)) {
            return $this;
        }

        return $this->whereIn($this->aliasField('creator_id'), $userIds);
    }

    /**
     * 获取部门IDs
     *
     * @param $roles
     * @return array
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/6/6
     */
    public function getDepartmentUserIdsBy($roles)
    {
        $userIds = [];

        $isAll = false;

        $user = request()->user();

        if (empty($roles)) {
            $roles = $user->getRoles();
        }

        foreach ($roles as $role) {
            switch ($role->data_range) {
                case Roles::ALL_DATA:
                    $isAll = true;
                    break;
                case Roles::SELF_CHOOSE:
                    $departmentIds = array_merge(array_column($role->getDepartments()->toArray(), 'id'));
                    $userIds = array_merge($userIds, $this->getUserIdsByDepartmentId($departmentIds));
                    break;
                case Roles::SELF_DATA:
                    $userIds[] = $user->id;
                    break;
                case Roles::DEPARTMENT_DOWN_DATA:
                    // 查一下下级部门
                    $departmentIds = Department::where('parent_id', $user->department_id)->column('id');
                    $departmentIds[] = $user->department_id;
                    $userIds = array_merge([$user->id], $this->getUserIdsByDepartmentId($departmentIds));
                    break;
                case Roles::DEPARTMENT_DATA:
                    $userIds = array_merge($userIds, $this->getUserIdsByDepartmentId([$user->department_id]));
                    break;
                default:
                    break;
            }

            // 如果有全部数据 直接跳出
            if ($isAll) {
                break;
            }
        }

        return $userIds;
    }

    /**
     * 获取UserID
     *
     * @time 2020年07月04日
     * @param $id
     * @return array
     */
    protected function getUserIdsByDepartmentId(array $id)
    {
        return Users::whereIn('department_id', $id)->column('id');
    }
}