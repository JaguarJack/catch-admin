<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Catch\Enums\Status;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        'role_id' => '='
    ];

    protected $hidden = ['pivot'];

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

    /**
     * is top menu
     *
     * @return bool
     */
    public function isTopMenu(): bool
    {
        return $this->type == MenuType::Top;
    }

    /**
     * actions
     *
     * @return HasMany
     */
    public function actions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->where('type', MenuType::Action);
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function storeBy(array $data): bool
    {
       $model = $this->fill($data);

       if ($model->isAction()) {
          $parentMenu =  $this->firstBy($model->parent_id, 'id');
          $model->setAttribute('module', $parentMenu->module);
          $model->setAttribute('permission_mark', $parentMenu->permission_mark . '@' . $data['permission_mark']);
          $model->setAttribute('route', '');
          $model->setAttribute('icon', '');
          $model->setAttribute('component', '');
          $model->setAttribute('redirect', '');
          return $model->setCreatorId()->save();
       }

       if ($model->isTopMenu()) {
           $data['route'] = '/' . trim($data['route'], '/');
       }

        return parent::storeBy($data);
    }


    /**
     * update data
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateBy($id, array $data): mixed
    {
        $model = $this->fill($data);

        if ($model->isAction()) {
            /* @var PermissionsModel $parentMenu */
            $parentMenu =  $this->firstBy($model->parent_id, 'id');
            $data['permission_mark'] = $parentMenu->permission_mark . '@' . $data['permission_mark'];
        }

        return parent::updateBy($id, $data);
    }
}
