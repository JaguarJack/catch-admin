<?php
namespace catchAdmin\permissions\model;

use catchAdmin\permissions\model\search\PermissionsSearch;
use catcher\base\CatchModel;
use think\Model;

class Permissions extends CatchModel
{
    use PermissionsSearch;

    protected $name = 'permissions';
    
    protected $field = [
        'id', //
        'permission_name', // 菜单名称
        'parent_id', // 父级ID
        'icon',
        'component', // 组件
        'redirect',
        'keepalive',
        'creator_id',
        'hidden',
        'module', // 模块
        'route', // 路由
        'permission_mark', // 权限标识
        'type', // 1 菜单 2 按钮
        'sort', // 排序字段
        'created_at', // 创建时间
        'updated_at', // 更新时间
        'deleted_at', // 删除状态，null 未删除 timestamp 已删除
    ];

    public const MENU_TYPE = 1;
    public const BTN_TYPE = 2;

    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const DELETE = 'delete';

    public function getList($isMenu = false)
    {
        return $this->catchSearch()
                    ->catchOrder()
                    ->when($isMenu, function ($query){
                        $query->where('type', self::MENU_TYPE);
                    })
                    ->select();
    }

    public function roles(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

  /**
   * 获取当前用户权限
   *
   * @time 2020年01月14日
   * @param array $permissionIds
   * @return \think\Collection
   * @throws \think\db\exception\DbException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\db\exception\DataNotFoundException
   */
    public static function getCurrentUserPermissions(array $permissionIds): \think\Collection
    {
        return parent::whereIn('id', $permissionIds)
                      ->field(['permission_name as title', 'id', 'parent_id',
                          'route', 'icon', 'component', 'redirect', 'module',
                          'keepalive as keepAlive', 'type', 'permission_mark', 'hidden'
                      ])
                      ->catchOrder()
                      ->select();
    }

    /**
     * 插入后回调 更新 level
     *
     * @time 2020年04月22日
     * @param Model $model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|bool|Model|void|null
     */
    public static function onAfterInsert(Model $model)
    {
        $model = self::where('id', $model->id)->find();

        if ($model && $model->parent_id) {
            $parent = self::where('id', $model->parent_id)->find();
            $level = $parent->level ? $parent->level . '-' . $parent->id : $parent->id;
            return $model->where('id', $model->id)->update([
                'level' => $level
            ]);
        }

        return true;
    }


    public function show($id)
    {
        $permission = $this->findBy($id);

        // 不能使用改属性判断，模型有该属性，使用数组方式
        // $permission->hidden
        $hidden = $permission['hidden'] == Permissions::ENABLE ? Permissions::DISABLE : Permissions::ENABLE;

        $nextLevelIds = $this->getNextLevel([$id]);

        $nextLevelIds[] = $id;

        return $this->whereIn('id', $nextLevelIds)->update([
            'hidden' => $hidden,
            'updated_at' => time(),
        ]);
    }

    /**
     * 获取 level ids
     *
     * @time 2020年09月06日
     * @param array $id
     * @param array $ids
     * @return array
     */
    protected function getNextLevel(array $id, &$ids = [])
    {
       $_ids = $this->whereIn('parent_id', $id)
             ->where('type', self::MENU_TYPE)
             ->column('id');

       if (count($_ids)) {
           $ids = array_merge($_ids, $this->getNextLevel($_ids, $ids));
       }

       return $ids;
    }
}
