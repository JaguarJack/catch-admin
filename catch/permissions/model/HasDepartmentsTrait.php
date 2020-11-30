<?php
namespace catchAdmin\permissions\model;

trait HasDepartmentsTrait
{
    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'role_has_departments', 'department_id', 'role_id');
    }

    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function getDepartments()
    {
        return $this->departments()->select();
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $departments
     * @return mixed
     */
    public function attachDepartments(array $departments)
    {
        if (empty($departments)) {
            return true;
        }

        sort($departments);

        return $this->departments()->attach($departments);
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $departments
     * @return mixed
     */
    public function detachDepartments(array $departments = [])
    {
        if (empty($departments)) {
            return $this->departments()->detach();
        }

        return $this->departments()->detach($departments);
    }
}
