<?php

namespace Modules\User\Models\Traits;

use Catch\CatchAdmin;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Modules\Permissions\Models\Jobs;
use Modules\Permissions\Models\Permissions;
use Modules\Permissions\Models\Roles;

trait UserRelations
{
    protected bool $isPermissionModuleEnabled = false;

    /**
     * init traits
     */
    public function initializeUserRelations(): void
    {
        $this->with = ['roles', 'jobs'];
    }

    /**
     * roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->getRolesModel(), 'user_has_roles', 'user_id', 'role_id');
    }

    /**
     * jobs
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany($this->getJobsModel(), 'user_has_jobs', 'user_id', 'job_id');
    }

    /**
     * permissions
     */
    public function withPermissions(): self
    {
        /* @var Permissions $permissionsModel */
        $permissionsModel = app($this->getPermissionsModel());
        if ($this->isSuperAdmin()) {
            $permissions = $permissionsModel->orderByDesc('sort')->get();
        } else {
            $permissionIds = Collection::make();
            $this->roles()->with('permissions')->get()
                /* @var Roles $role */
                ->each(function (Roles $role) use (&$permissionIds) {
                    $rolePermissionIds = $role->permissions?->pluck('id');
                    if ($rolePermissionIds) {
                        $permissionIds = $permissionIds->merge($rolePermissionIds);
                    }
                });

            $permissions = $permissionsModel->whereIn('id', $permissionIds->unique())->orderByDesc('sort')->get();
        }

        $this->setAttribute('permissions', $permissions->each(function ($permission) {
            $permission->setAttribute('hidden', $permission->isHidden());
            $permission->setAttribute('keepalive', $permission->isKeepAlive());
        }));

        return $this;
    }

    /**
     * permission module controller.action
     */
    public function can(?string $permission = null): bool
    {
        if (! $this->isPermissionModuleEnabled) {
            return true;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $this->withPermissions();

        $actions = Collection::make();

        $this->getAttribute('permissions')->each(function ($permission) use (&$actions) {
            if ($permission->isAction()) {
                [$controller, $action] = explode('@', $permission->permission_mark);

                $actions->add(CatchAdmin::getModuleControllerNamespace($permission->module) . ucfirst($controller) . 'Controller@' . $action);
            }
        });

        if ($permission) {
            [$module, $controller, $action] = explode('@', $permission);

            if (! CatchAdmin::isModulePathExist($module)) {
                // todo
            } else {
                $permission = CatchAdmin::getModuleControllerNamespace($module) . ucfirst($controller) . 'Controller@' . $action;
            }
        }

        return $actions->contains($permission ?: Route::currentRouteAction());
    }

    /**
     * get RolesModel
     *
     * @see \Modules\Permissions\Models\Roles
     */
    protected function getRolesModel(): string
    {
        return Roles::class;
    }

    /**
     * get JobsModel
     *
     * @see \Modules\Permissions\Models\Jobs
     */
    protected function getJobsModel(): string
    {
        return Jobs::class;
    }

    /**
     * get PermissionsModel
     *
     *@see \Modules\Permissions\Models\Permissions
     */
    protected function getPermissionsModel(): string
    {
        return Permissions::class;
    }
}
