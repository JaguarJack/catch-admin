<?php
namespace catchAdmin\user\model;

use catchAdmin\permissions\model\HasRolesTrait;
use catcher\base\CatchModel;

class Users extends CatchModel
{
    use HasRolesTrait;

    protected $name = 'users';

    protected $field = [
            'id', // 
			'username', // 用户名
			'password', // 用户密码
			'email', // 邮箱 登录
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
     * @param $search
     * @throws \think\db\exception\DbException
     * @return \think\Paginator
     */
    public function getList($search): \think\Paginator
    {
        return (($search['trash'] ?? false) ? static::onlyTrashed() : $this)
                    ->field(['id', 'username', 'email', 'status','last_login_time','last_login_ip', 'created_at', 'updated_at'])
                    ->when($search['username'] ?? false, function ($query) use ($search){
                        $query->whereLike('username', '%' . $search['username'] . '%');
                    })
                    ->when($search['email'] ?? false, function ($query) use ($search){
                        $query->whereLike('email', '%' . $search['email'] . '%');
                    })
                    ->when($search['status'] ?? false, function ($query) use ($search){
                        $query->where('status', $search['status']);
                    })
                    ->order('id', 'desc')
                    ->paginate($search['limit'] ?? $this->limit);
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
        $roles = $uid ? $this->findBy($uid)->getRoles() : $this->getRoles();

        $permissionIds = [];
        foreach ($roles as $role) {
            $permissionIds = array_merge($permissionIds, $role->getPermissions()->column('id'));
        }

        return array_unique($permissionIds);
    }
}
