<?php
namespace catchAdmin\permissions\model;

use catchAdmin\permissions\model\search\UserSearch;
use catcher\base\CatchModel;
use catcher\exceptions\FailedException;
use catcher\Utils;

class Users extends CatchModel
{
    use HasRolesTrait;
    use HasJobsTrait;
    use UserSearch;
    
    protected $name = 'users';

    protected $field = [
            'id', // 
			'username', // 用户名
			'password', // 用户密码
			'email', // 邮箱 登录
            'avatar', // 头像
            'remember_token',
            'creator_id', // 创建者ID
            'department_id', // 部门ID
			'status', // 用户状态 1 正常 2 禁用
			'last_login_ip', // 最后登录IP
			'last_login_time', // 最后登录时间
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，0未删除 >0 已删除
    ];

    /**
     * set password
     *
     * @time 2019年12月07日
     * @param $value
     * @return false|string
     */
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 用户列表
     *
     * @time 2019年12月08日
     * @throws \think\db\exception\DbException
     * @return \think\Paginator
     */
    public function getList(): \think\Paginator
    {
        return $this->withoutField(['updated_at'], true)
                    ->catchSearch()
                    ->catchLeftJoin(Department::class, 'id', 'department_id', ['department_name'])
                    ->order($this->aliasField('id'), 'desc')
                    ->paginate();
    }

    /**
     * 获取权限
     *
     * @time 2019年12月12日
     * @param $uid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    public function getPermissionsBy($uid = 0): array
    {
        // 获取超级管理配置 超级管理员全部权限
        if ($uid == config('catch.permissions.super_admin_id')) {
            return Permissions::select()->column('id');
        }

        $roles = $uid ? $this->findBy($uid)->getRoles() : $this->getRoles();

        $permissionIds = [];
        foreach ($roles as $role) {
            $permissionIds = array_merge($permissionIds, $role->getPermissions()->column('id'));
        }

        return array_unique($permissionIds);
    }
	
	 /**
     * 后台根据权限标识判断用户是否拥有某个权限
     * @param string $permission_mark
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * 用法  request()->user()->can('permission@create');
     */
    public function can($permission_mark)
    {
        // 超级管理员直接返回true
        if (Utils::isSuperAdmin()){
            return true;
        }
        // 查询当前用户的权限
        return in_array(
            Permissions::where('permission_mark',$permission_mark)->value('id') ? : 0,
            $this->getPermissionsBy()
        );
    }
}
