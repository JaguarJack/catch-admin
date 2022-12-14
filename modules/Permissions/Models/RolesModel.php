<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;

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
class RolesModel extends Model
{
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


    /**
     * @var bool
     */
    protected bool $isPaginate = false;

    /**
     * @var array
     */
    public array $searchable = [
        'role_name' => 'like',
    ];


    protected bool $asTree = true;
}
