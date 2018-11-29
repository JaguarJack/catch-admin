<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14 0014
 * Time: 下午 17:42
 */
namespace app\admin\validates;

class RoleValidate extends AbstractValidate
{
	protected  $rule = [
		'name|角色名'   => 'require|min:3|max:15|chs|unique:roles',
	];
}