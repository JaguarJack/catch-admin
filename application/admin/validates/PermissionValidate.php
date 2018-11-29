<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14 0014
 * Time: 下午 18:21
 */

namespace app\admin\validates;

class PermissionValidate extends AbstractValidate
{
	protected $rule = [
		'name|菜单名称'         => 'require|min:2|max:10|chs|unique:permissions',
		'module|模块名称'       => 'require|min:2|max:10|alpha',
		'controller|控制器名称' => 'require|min:2|max:50|alpha',
		'action|方法名称'       => 'require|min:2|max:50|alpha',
	];
}