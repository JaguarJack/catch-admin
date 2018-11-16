<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13 0013
 * Time: 上午 9:33
 */
namespace app\behavior;

class LoginRecord
{
	public function run($params)
	{
		$user = $params['user'];
		## 登录记录
		$user->login_at	= date('Y-m-d h:i:s', time());
		$user->login_ip	= request()->ip();
		$user->save();
	}
}