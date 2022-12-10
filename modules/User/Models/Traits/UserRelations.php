<?php
namespace Modules\User\Models\Traits;


use Catch\CatchAdmin;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserRelations
{

    /**
     * init traits
     */
    public function initializeUserRelations(): void
    {
       if (app(ModuleRepository::class)->enabled('permissions')) {
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
        /* @var \Modules\Permissions\Models\PermissionsModel $permissionsModel */
        $permissionsModel = app($this->getPermissionsModel());

        if ($this->isSuperAdmin()) {
            $permissions = $permissionsModel->get();
        } else {
            $roles = app($this->getRolesModel())->with(['permissions'])->get();

            $permissions = [];
        }

        $this->setAttribute('permissions', $permissions->each(fn($permission) => $permission->setAttribute('hidden', $permission->isHidden())));

        return $this;
    }


    /**
     * get RolesModel
     *
     * @see \Modules\Permissions\Models\RolesModel
     * @return string
     */
    protected function getRolesModel(): string
    {
        return '\\' . CatchAdmin::getModuleModelNamespace('permissions') . 'RolesModel';
    }


    /**
     * get JobsModel
     *
     * @see \Modules\Permissions\Models\JobsModel
     * @return string
     */
    protected function getJobsModel(): string
    {
        return '\\'. CatchAdmin::getModuleModelNamespace('permissions') . 'JobsModel';
    }

    /**
     * get PermissionsModel
     *
     * @see \Modules\Permissions\Models\PermissionsModel
     * @return string
     */
    protected function getPermissionsModel(): string
    {
        return '\\'. CatchAdmin::getModuleModelNamespace('permissions') . 'PermissionsModel';
    }
}
