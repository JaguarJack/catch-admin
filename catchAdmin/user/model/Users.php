<?php
namespace catchAdmin\user\model;

use catcher\Model;

class Users extends Model
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
			'delete_at', // 删除状态，0未删除 >0 已删除
			   
    ];  
}