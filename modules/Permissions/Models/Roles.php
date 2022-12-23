<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Permissions\Models\Traits\DataRange;

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
*/
class Roles extends Model
{
    use DataRange;

    protected $table = 'roles';

    protected $fillable = ['id', 'role_name', 'identify', 'parent_id', 'description', 'data_range', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @var array
     */
    protected array $fields = ['id', 'role_name','identify','parent_id','description','data_range', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected array $form = ['role_name','identify','parent_id','description','data_range'];

    protected array $formRelations = ['permissions'];

    /**
     * @var bool
     */
    protected bool $isPaginate = false;

    /**
     * @var array
     */
    public array $searchable = [
        'role_name' => 'like',

        'id' => '<>'
    ];

    protected bool $asTree = true;


    /**
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }


    /**
     * departments
     *
     * @return BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Departments::class, 'role_has_departments', 'role_id', 'department_id');
    }
}
