<?php
namespace catchAdmin\permissions\model;

use catcher\base\BaseModel;

class Permissions extends BaseModel
{
    protected $name = 'permissions';
    
    protected $field = [
            'id', // 
			'name', // 菜单名称
			'parent_id', // 父级ID
			'route', // 路由
			'permission_mark', // 权限标识
			'type', // 1 菜单 2 按钮
			'sort', // 排序字段
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，null 未删除 timestamp 已删除
			   
    ];

    public function roles(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_has_permissions');
    }
}