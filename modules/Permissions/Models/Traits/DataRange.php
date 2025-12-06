<?php

declare(strict_types=1);

namespace Modules\Permissions\Models\Traits;

use Catch\Facade\Admin;
use Illuminate\Support\Collection;
use Modules\Permissions\Enums\DataRange as DataRangeEnum;
use Modules\Permissions\Models\Departments;
use Modules\Permissions\Models\Roles;

/**
 * @method aliasField(string $field)
 */
trait DataRange
{
    public function scopeDataRange($query, array|Collection $roles = []): mixed
    {
        $currenUser = Admin::currentLoginUser();

        if ($currenUser->isSuperAdmin()) {
            return $query;
        }

        $userIds = $this->getDepartmentUserIdsBy($roles, $currenUser);

        if ($userIds->isEmpty()) {
             return $query;
        }

        return $query->whereIn($this->aliasField('creator_id'), $userIds);
    }

    /**
     * get department ids
     */
    public function getDepartmentUserIdsBy(array $roles, $currentUser): Collection
    {
        $userIds = Collection::make();

        if (empty($roles)) {
            $roles = $currentUser->roles()->get();
        }

        /* @var Roles $role */
        foreach ($roles as $role) {
            if (DataRangeEnum::All_Data->assert($role->data_range)) {
                return Collection::make();
            }

            if (DataRangeEnum::Personal_Choose->assert($role->data_range)) {
                $userIds = $userIds->merge($this->getUserIdsByDepartmentId($role->departments()->pluck('id')));
            }

            if (DataRangeEnum::Personal_Data->assert($role->data_range)) {
                $userIds = $userIds->push($currentUser->id);
            }

            if (DataRangeEnum::Department_Data->assert($role->data_range)) {
                $userIds = $userIds->merge(
                    $this->getUserIdsByDepartmentId([$currentUser->department_id])
                );
            }

            if (DataRangeEnum::Department_DOWN_Data->assert($role->data_range)) {
                $departmentsId = [$currentUser->department_id];

                $departmentModel = new Departments();

                $departmentIds = $departmentModel->findFollowDepartments($departmentsId);

                $userIds = $userIds->merge($this->getUserIdsByDepartmentId($departmentIds))->push($currentUser->id);
            }
        }

        // 如果查出来没有任何用户，说明无法查看数据
        if ($userIds->isEmpty()) {
            $userIds = $userIds->push(0);
        }

        return $userIds->unique();
    }

    /**
     * get user ids by department is
     */
    protected function getUserIdsByDepartmentId(array|Collection $departmentIds): Collection
    {
        $userModel = app(getAuthUserModel());

        return $userModel->whereIn('department_id', $departmentIds)->pluck('id');
    }
}
