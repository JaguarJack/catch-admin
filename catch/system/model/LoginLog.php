<?php

namespace catchAdmin\system\model;

use catcher\traits\db\BaseOptionsTrait;
use catchAdmin\system\model\search\LoginLogSearch;

class LoginLog extends \think\Model
{
	use BaseOptionsTrait, LoginLogSearch;

	protected $name = 'login_log';

	protected $field = [
		'id', // 
		'login_name', // 用户名
		'login_ip', // 登录地点ip
		'browser', // 浏览器
		'os', // 操作系统
		'login_at', // 登录时间
		'status', // 1 成功 2 失败   
	];

	public function getList()
	{
		return $this->catchSearch()
			->order('id', 'desc')
			->paginate();
	}
}
