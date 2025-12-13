<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Catch\CatchAdmin;
use Catch\Enums\Status;
use Catch\Exceptions\FailedException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
 * @property $active_menu
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Permissions extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['id', 'parent_id', 'permission_name', 'route', 'icon', 'module', 'permission_mark', 'component', 'redirect', 'keepalive', 'type', 'hidden', 'active_menu', 'sort', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'parent_id', 'permission_name', 'route', 'icon', 'module', 'permission_mark', 'component', 'redirect', 'keepalive', 'type', 'hidden', 'active_menu', 'sort', 'created_at', 'updated_at'];

    protected bool $isPaginate = false;

    protected array $form = ['parent_id', 'permission_name', 'route', 'icon', 'module', 'permission_mark', 'component', 'redirect', 'keepalive', 'type', 'active_menu', 'hidden', 'sort'];

    public array $searchable = [
        'permission_name' => 'like',

        'role_id' => '=',
    ];

    protected $hidden = ['pivot'];

    /**
     * 子父级字段同步
     *
     * @var array|string[]
     */
    protected array $syncParentFields = ['status', 'hidden'];

    /**
     * default permission actions
     *
     * @var array|string[]
     */
    protected array $defaultActions = [
        'index' => '列表',
        'store' => '新增',
        'show' => '读取',
        'update' => '更新',
        'destroy' => '删除',
        'enable' => '禁用/启用',
        'import' => '导入',
        'export' => '导出',
    ];

    protected bool $asTree = true;

    /**
     * @var string[]
     */
    protected $casts = [
        'type' => MenuType::class,

        'status' => MenuStatus::class,
    ];

    /**
     * is inner
     */
    public function isInner(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == 1
        );
    }

    /**
     * is hidden
     */
    public function isHidden(): bool
    {
        return $this['hidden'] == Status::Disable->value();
    }

    /**
     * action type
     */
    public function isAction(): bool
    {
        return $this->type === MenuType::Action;
    }

    /**
     * is top menu
     */
    public function isTopMenu(): bool
    {
        return $this->type === MenuType::Top;
    }

    /**
     * is menu
     */
    public function isMenu(): bool
    {
        return $this->type === MenuType::Menu;
    }

    /**
     * is keepalive
     *
     * @return bool
     */
    public function isKeepAlive(): bool
    {
        return $this->keepalive == 1;
    }

    /**
     * 获取菜单的动作，也是就是对应操作
     */
    public function actions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->where('type', MenuType::Action);
    }

    /**
     * component 添加 .vue 后缀
     *
     * @return Attribute
     */
    public function component(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! $value) {
                    return $value;
                }

                return pathinfo($value, PATHINFO_EXTENSION) == 'vue' ? $value : $value.'.vue';
            }
        );
    }

    /**
     * 保存菜单
     *
     * @param  array  $data
     * @return mixed
     */
    public function storeBy(array $data): mixed
    {
        return DB::transaction(function () use ($data) {
            if ($data['actions'] ?? false) {
                /* @var static $parentMenu */
                $parentMenu = $this->firstBy(value: $data['parent_id'], field: 'id');

                if (! $parentMenu->isMenu()) {
                    return false;
                }
                try {
                    $actions = CatchAdmin::getControllerActions($parentMenu->module, $parentMenu->permission_mark);
                } catch (\ReflectionException $e) {
                    throw new FailedException('没有可生成的 action');
                }

                foreach ($actions as $k => $action) {
                    $this->addAction($this->newInstance([
                        'type' => MenuType::Action->value(),
                        'parent_id' => $data['parent_id'],
                        // 如果不是 defaultActions 中的，则默认使用方法名称作为 permission_name
                        'permission_name' => $this->defaultActions[$action] ?? $action,
                        'permission_mark' => $action,
                        'sort' => $k + 1,
                        'created_at' => time(),
                        'updated_at' => time(),
                    ]), $parentMenu);
                }

                return true;
            }

            if (! MenuType::Top->asset($data['type']) && ! $data['parent_id']) {
                throw new FailedException('请选择父级菜单');
            }

            $model = $this->fill($data);

            if ($model->isAction()) {
                $parentMenu = $this->firstBy($model->parent_id, 'id');

                return $this->addAction($model, $parentMenu);
            }

            if ($model->isTopMenu()) {
                if ($this->where('module', $model->module)->where('type', MenuType::Top)->first()) {
                    throw new FailedException('模块已存在目录类型菜单，模块只允许创建一个目录类型菜单');
                }

                $data['route'] = '/'.trim($data['route'], '/');
            }

            if (isset($data['component'])) {
                $data['component'] = Str::of($data['component'])->replace('\\', '/')->toString();
            }

            return parent::storeBy($data);
        });
    }

    /**
     * add action
     *
     * @param  Permissions  $parent
     */
    protected function addAction($model, mixed $parent): mixed
    {
        $model->setAttribute('module', $parent->module);
        $model->setAttribute('permission_mark', $parent->permission_mark.'@'.$model->permission_mark);
        $model->setAttribute('route', '');
        $model->setAttribute('icon', '');
        $model->setAttribute('component', '');
        $model->setAttribute('redirect', '');

        if ($this->where('module', $model->getAttribute('module'))->where('permission_mark', $model->getAttribute('permission_mark'))->first()) {
            return false;
        }

        return $model->setCreatorId()->save();
    }

    /**
     * update data
     */
    public function updateBy($id, array $data): mixed
    {
        if (! MenuType::Top->asset($data['type']) && ! $data['parent_id']) {
            throw new FailedException('请选择父级菜单');
        }

        $model = $this->fill($data);

        if ($model->isAction()) {
            /* @var Permissions $parentMenu */
            $parentMenu = $this->firstBy($model->parent_id, 'id');
            $data['permission_mark'] = $parentMenu->permission_mark.'@'.$data['permission_mark'];
        }

        if (isset($data['component'])) {
            $data['component'] = Str::of($data['component'])->replace('\\', '/')->toString();
        }

        return parent::updateBy($id, $data);
    }
}
