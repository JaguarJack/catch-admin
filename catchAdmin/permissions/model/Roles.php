<?php
namespace catchAdmin\permissions\model;

use catchAdmin\user\model\Users;
use catcher\base\BaseModel;

class Roles extends BaseModel
{
    protected $name = 'roles';
    
    protected $field = [
            'id', // 
			'name', // 角色名
			'parent_id', // 父级ID
			'description', // 角色备注
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，0未删除 >0 已删除
			   
    ];

    public function getList($search)
    {
        return $this->when($search['name'] ?? false, function ($query) use ($search){
                $query->whereLike('name', $search['name']);
        })->paginate($search['limit'] ?? $this->limit);
    }

    /**
     *
     * @time 2019年12月08日
     * @return \think\model\relation\BelongsToMany
     */
    public function users(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Users::class, 'user_has_roles');
    }

    /**
     *
     * @time 2019年12月09日
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    /**
     *
     * @time 2019年12月08日
     * @param $rid
     * @return mixed
     */
    public function getRoles($rid)
    {
        return $this->findBy($rid)->permissions()->get();
    }

    /**
     *
     * @time 2019年12月08日
     * @param $rid
     * @param array $roles
     * @return mixed
     */
    public function attach($rid, array $roles)
    {
        return $this->findBy($rid)->permissions()->attach($roles);
    }

    /**
     *
     * @time 2019年12月08日
     * @param $rid
     * @param array $roles
     * @return mixed
     */
    public function detach($rid, array $roles)
    {
        return $this->findBy($rid)->permissions()->detach($roles);
    }
}