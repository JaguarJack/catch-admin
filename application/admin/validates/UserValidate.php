<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 下午 16:38
 */

namespace app\admin\validates;

class UserValidate extends AbstractValidate
{
	protected  $rule = [
		'name|用户名'   => 'require|min:3|max:15|alphaNum|unique:users',
		'email|邮箱'    => 'email|unique:users',
		'password|密码' => 'confirm|min:6|max:20|alphaDash',
	];
}