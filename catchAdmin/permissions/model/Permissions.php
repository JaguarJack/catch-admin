<?php
namespace catchAdmin\permissions\model;

use catcher\base\CatchModel;

class Permissions extends CatchModel
{
    protected $name = 'permissions';
    
    protected $field = [
            'id', // 
			'permission_name', // 菜单名称
			'parent_id', // 父级ID
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

    public function getList($search = [])
    {
        return $this->when($search['name'] ?? false, function ($query) use ($search){
                $query->whereLike('name', $search['name']);
            })
            ->when($search['id'] ?? false, function ($query) use ($search){
                $query->where('parent_id', $search['id'])
                    ->whereOr('id', $search['id']);
            })
            ->order('sort', 'desc')
            ->order('id', 'desc')
            ->select()
            ->toArray();
    }

    public function roles(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}