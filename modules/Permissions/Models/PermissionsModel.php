<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Catch\Enums\Status;
use Modules\Permissions\Enums\MenuStatus;
use Modules\Permissions\Enums\MenuType;

/**
 * @property $id
 * @property $parent_id
 * @property $permission_name
 * @property $route
 * @property $icon
 * @property $module
 * @property $permission_mark
 * @property $component
 * @property $redirect
 * @property $keepalive
 * @property $type
 * @property $hidden
 * @property $sort
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
*/
class PermissionsModel extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['id', 'parent_id', 'permission_name', 'route', 'icon', 'module', 'permission_mark', 'component', 'redirect', 'keepalive', 'type', 'hidden', 'sort', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @var array
     */
    protected array $fields = ['id','parent_id','permission_name','route','icon','module','permission_mark','component','redirect','keepalive','type','hidden','sort','created_at','updated_at'];

    protected bool $isPaginate = false;

    /**
     * @var array
     */
    protected array $form = ['parent_id','permission_name','route','icon','module','permission_mark','component','redirect','keepalive','type','hidden','sort'];

    /**
     * @var array
     */
    public array $searchable = [
        'permission_name' => 'like',
    ];


    /**
     * @var bool
     */
    protected bool $asTree = true;

    /**
     * @var string[]
     */
    protected $casts = [
        'type' => MenuType::class,

        'status' => MenuStatus::class
    ];


    /**
     * is hidden
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden === Status::Disable;
    }

    /**
     * action type
     *
     * @return bool
     */
    public function isAction(): bool
    {
        return $this->type == MenuType::Action;
    }
}
