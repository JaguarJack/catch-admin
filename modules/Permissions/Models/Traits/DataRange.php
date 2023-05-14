<?php
declare(strict_types=1);

namespace Modules\Permissions\Models\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Permissions\Models\Departments;
use Modules\Permissions\Models\Roles;
use Modules\Permissions\Enums\DataRange as DataRangeEnum;

/**
 * @method aliasField(string $field)
 */
trait DataRange
{

    /**
     *
     * @param $query
     * @param array|Collection $roles
     * @return mixed
     */
    public function scopeDataRange($query, array|Collection $roles = []): mixed
    {
        $currenUser = Auth::guard(getGuardName())->user();

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
     *
     * @param array $roles
     * @param $currentUser
     * @return Collection
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

        return $userIds->unique();
    }


    /**
     * get user ids by department is
     *
     * @param array|Collection $departmentIds
     * @return Collection
     */
    protected function getUserIdsByDepartmentId(array|Collection $departmentIds): Collection
    {
        $userModel = app(getAuthUserModel());

        return $userModel->whereIn('department_id', $departmentIds)->pluck('id');
    }
}
