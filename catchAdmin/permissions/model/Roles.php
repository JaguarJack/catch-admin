<?php
namespace catchAdmin\permissions\model;

use catchAdmin\user\model\Users;
use catcher\base\CatchModel;

class Roles extends CatchModel
{
    protected $name = 'roles';
    
    protected $field = [
            'id', // 
			'role_name', // 角色名
			'parent_id', // 父级ID
			'description', // 角色备注
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，0未删除 >0 已删除
			   
    ];

    public function getList($search = [])
    {
        return $this->when($search['name'] ?? false, function ($query) use ($search){
                    $query->whereLike('name', $search['name']);
                })
                ->when($search['id'] ?? false, function ($query) use ($search){
                    $query->where('parent_id', $search['id'])
                          ->whereOr('id', $search['id']);
                })
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

    /**
     *
     * @time 2019年12月09日
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions()->select();
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $roles
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function attach(array $roles)
    {
        if (empty($roles)) {
            return true;
        }

        return $this->permissions()->attach($roles);
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $roles
     * @return mixed
     */
    public function detach(array $roles = [])
    {
        if (empty($roles)) {
            return $this->permissions()->detach();
        }

        return $this->permissions()->detach($roles);
    }
}