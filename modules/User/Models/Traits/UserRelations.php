<?php

namespace Modules\User\Models\Traits;

use Catch\CatchAdmin;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Permissions\Models\Permissions;

trait UserRelations
{
    protected bool $isPermissionModuleEnabled = false;

    /**
     * init traits
     */
    public function initializeUserRelations(): void
    {
        $this->isPermissionModuleEnabled = app(ModuleRepository::class)->enabled('permissions');

        if ($this->isPermissionModuleEnabled) {
            $this->with = ['roles', 'jobs'];
        }
    }

    /**
     * roles
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->getRolesModel(), 'user_has_roles', 'user_id', 'role_id');
    }

    /**
     * jobs
     *
     * @return BelongsToMany
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
        if (! $this->isPermissionModuleEnabled) {
            return $this;
        }

        /* @var Permissions $permissionsModel */
        $permissionsModel = app($this->getPermissionsModel());
        if ($this->isSuperAdmin()) {
            $permissions = $permissionsModel->orderByDesc('sort')->get();
        } else {
            $permissionIds = Collection::make();
            $this->roles()->with('permissions')->get()
                ->each(function ($role) use (&$permissionIds) {
                    $permissionIds = $permissionIds->concat($role->permissions?->pluck('id'));
                });

            $permissions = $permissionsModel->whereIn('id', $permissionIds->unique())->orderByDesc('sort')->get();
        }

        $this->setAttribute('permissions', $permissions->each(fn ($permission) => $permission->setAttribute('hidden', $permission->isHidden())));
        return $this;
    }

    /**
     *
     * permission module controller.action
     *
     * @param string|null $permission
     * @return bool
     */
    public function can(string $permission = null): bool
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

                $actions->add(CatchAdmin::getModuleControllerNamespace($permission->module). ucfirst($controller).'Controller@'.$action);
            }
        });

        // 自定义权限判断
        if ($permission) {
            [$module, $controller, $action] = explode('@', $permission);

            $permission = CatchAdmin::getModuleControllerNamespace($module). ucfirst($controller) .'Controller@'.$action;
        }

        return $actions->contains($permission ?: Route::currentRouteAction());
    }

    /**
     * get RolesModel
     *
     * @see \Modules\Permissions\Models\Roles
     * @return string
     */
    protected function getRolesModel(): string
    {
        return '\\'.CatchAdmin::getModuleModelNamespace('permissions').'Roles';
    }


    /**
     * get JobsModel
     *
     * @see \Modules\Permissions\Models\Jobs
     * @return string
     */
    protected function getJobsModel(): string
    {
        return '\\'.CatchAdmin::getModuleModelNamespace('permissions').'Jobs';
    }

    /**
     * get PermissionsModel
     *
     * @return string
     *@see \Modules\Permissions\Models\Permissions
     */
    protected function getPermissionsModel(): string
    {
        return '\\'.CatchAdmin::getModuleModelNamespace('permissions').'Permissions';
    }
}
