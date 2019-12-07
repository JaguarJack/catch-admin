<?php
namespace catchAdmin\user\model;

use catcher\base\BaseModel;

class Users extends BaseModel
{
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

    public function getList($search)
    {
        return (($search['trash'] ?? false) ? static::onlyTrashed() : $this)->when($search['username'] ?? false, function ($query) use ($search){
                        return $query->whereLike('username', $search['username']);
                    })
                    ->when($search['email'] ?? false, function ($query) use ($search){
                        return $query->whereLike('email', $search['email']);
                    })
                    ->when($search['status'] ?? false, function ($query) use ($search){
                        return $query->where('status', $search['status']);
                    })->paginate($search['limit'] ?? $this->limit);
    }
}