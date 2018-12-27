<?php
namespace app\admin\controller;

use app\traits\Auth;
use think\Controller;

class Login extends Controller
{
	use Auth;

	protected $redirect = '/index';

	/**
	 * Login Page
	 *
	 * @return mixed
	 */
	public function login()
	{
		// 登录逻辑
		if ($this->request->isPost()) {
			$this->authLogin($this->request);
		}

		return $this->fetch('/index/login');
	}

	/**
	 * 登出
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\think\response\Redirect
	 */
	public function logout()
	{
		$this->authLogout();

		return redirect(url('login'));
	}

	/**
	 * 验证规则
	 *
	 * @time at 2018年11月13日
	 * @return array
	 */
	protected function rule()
	{
		return [
			$this->name()    => 'require',
			'password|密码'  => 'require',
			'captcha|验证码' => 'require|captcha'
		];
	}

}