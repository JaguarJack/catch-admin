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
        'keep_alive',
        'hide_children_in_menu',
        'creator_id',
        'module', // 模块
        'route', // 路由
        'method', // 请求方法
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
                    ->order('sort', 'desc')
                    ->order('id', 'desc')
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
                          'route', 'icon', 'component', 'redirect',
                          'keep_alive as keepAlive', 'hide_children_in_menu', 'type'
                      ])
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
}
