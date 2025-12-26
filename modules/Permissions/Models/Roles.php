<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property $role_name
 * @property $identify
 * @property $parent_id
 * @property $description
 * @property $data_range
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 * @property null|Collection<Permissions> $permissions
 * @property null|Collection<Departments> $departments
 */
class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = ['id', 'role_name', 'identify', 'parent_id', 'description', 'data_range', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'role_name', 'identify', 'parent_id', 'description', 'data_range', 'created_at', 'updated_at'];

    protected array $form = ['role_name', 'identify', 'parent_id', 'description', 'data_range'];

    protected array $formRelations = ['permissions', 'departments'];

    protected bool $isPaginate = false;

    public array $searchable = [
        'role_name' => 'like',

        'id' => '<>',
    ];

    protected bool $asTree = true;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    /**
     * departments
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Departments::class, 'role_has_departments', 'role_id', 'department_id');
    }

    /**
     * get role's permissions
     */
    public function getPermissions(): Collection
    {
        return $this->permissions()->get();
    }

    /**
     * get role's departments
     */
    public function getDepartments(): Collection
    {
        return $this->departments()->get();
    }
}
