<?php
namespace catchAdmin\permissions\model;

use catchAdmin\permissions\model\search\RolesSearch;
use catcher\base\CatchModel;

class Roles extends CatchModel
{
    use HasDepartmentsTrait;
    use HasPermissionsTrait;
    use RolesSearch;

    protected $name = 'roles';

    public const ALL_DATA = 1; // 全部数据
    public const SELF_CHOOSE = 2; // 自定义数据
    public const SELF_DATA = 3; // 本人数据
    public const DEPARTMENT_DATA = 4; // 部门数据
    public const DEPARTMENT_DOWN_DATA = 5; // 部门及以下数据


    protected $field = [
            'id', // 
			'role_name', // 角色名
			'parent_id', // 父级ID
      'creator_id', // 创建者
      'data_range', // 数据范围
			'description', // 角色备注
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，0未删除 >0 已删除
			   
    ];

    public function getList()
    {
        return $this->catchSearch()
                    ->order('id', 'desc')
                    ->select()
                    ->toArray();
    }

    /**
     *
     * @time 2019年12月08日
     * @return \think\model\relation\BelongsToMany
     */
    public function users(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Users::class, 'user_has_roles', 'uid', 'role_id');
    }


    public static function getDepartmentUserIdsBy($roles)
    {
        $uids = [];

        $isAll = false;

        $user = request()->user();

        foreach ($roles as $role) {
            switch ($role->data_range) {
              case self::ALL_DATA:
                    $isAll = true;
                    break;
              case self::SELF_CHOOSE:
                    $departmentIds = array_merge(array_column($role->getDepartments()->toArray(), 'id'));
                    $uids = array_merge($uids, Users::getUserIdsByDepartmentIds($departmentIds));
                    break;
              case self::SELF_DATA:
                    $uids[] = $user->id;
                    break;
              case self::DEPARTMENT_DOWN_DATA:
              case self::DEPARTMENT_DATA:
                    $uids = array_merge($uids, Users::getUserIdsByDepartmentIds([$user->department_id]));
                    break;
              default:
                    break;
            }

            // 如果有全部数据 直接跳出
            if ($isAll) {
              break;
            }
        }

        return $uids;
    }
}
