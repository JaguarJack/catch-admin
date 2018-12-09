<?php

namespace app\traits;

use think\Request;
use think\Validate;
use think\facade\Session;
use think\facade\Cookie;
use app\model\UserModel as User;
use app\behavior\LoginRecord;

trait Auth
{
	protected $loginUserKey = 'user';

	public function authLogin(Request $request)
	{
		$err = $this->validateLogin($request);
		if ($err) {
			$this->error($err);
		}

		// 正常输入登录
		$userModel = new User();
		$field = explode('|', $this->name());
		$user = $userModel::where($field[0], $request->param($field[0]))->find();

		if (!$user) {
			$this->error('登录失败');
		}
		if (password_verify($request->param('password'), $user->password)) {
			Session::set($this->loginUserKey, $user);
			# 记住登录
			$this->LoginRemember($user, $request);
			# 登录记录
			hook(LoginRecord::class, ['user' => $user]);
			$this->success('登录成功', url($this->redirect));
		}

		$this->error('登录失败');

	}

	/**
	 * 记住登录
	 * @return bool
	 */
	public function rememberLogin()
	{
		// 如果记住登录
		if (!Session::get($this->loginUserKey) && Cookie::get('remember_token') && $this->checkRememberToken()) {
			return true;
		}

		return false;
	}

	/**
	 * 退出
	 * @return void
	 */
	public function authLogout()
	{
		$user = Session::get($this->loginUserKey);
		$this->deleteToken($user);
		Session::delete($this->loginUserKey);
	}

	protected function deleteToken($user)
	{
		if ($user->remember_token) {
			$user->remember_token = null;
			$user->save();
			Cookie::delete('remember_token');
		}
	}
	/**
	 * 验证
	 * @param Request $request
	 * @return array|bool
	 */
	protected function validateLogin(Request $request)
	{
		$validate = new Validate($this->rule());
		if (!$validate->check($request->except(['remember']))) {
			return $validate->getError();
		}

		return false;
	}

	/**
	 * 登录验证规则
	 * @return array
	 */
	protected function rule()
	{
		return [
			$this->name()    => 'require|token|alphaDash',
			'password|密码'  => 'require|alphaDash',
			'captcha|验证码' => 'require|captcha'
		];
	}

	/**
	 * 设置登录字段
	 *
	 * @return string
	 */
	protected function name()
	{
		return 'name|用户名';
	}

	/**
	 * Remember Token
	 *
	 * @return string
	 */
	public function generateRememberToken()
	{
		return uniqid(md5(time()+rand(10000, 99999)));
	}

	/**
	 * 加密 TOKEN
	 *
	 * @param $user_id
	 * @param $remember_token
	 * @return string
	 */
	protected function secretRememberToken($user_id, $remember_token)
	{
		list($key, $method, $iv) = $this->getSecret();
		return base64_encode(openssl_encrypt($user_id . ':' . $remember_token, $method, $key, OPENSSL_RAW_DATA, $iv));
	}

	/**
	 * 检查remember token 是否正确
	 *
	 * @return bool
	 */
	protected function checkRememberToken()
	{
		if (!Cookie::has('remember_token')) {
			return false;
		}
		$rememberToken = Cookie::get('remember_token');
		// 解密
		list($key, $method, $iv) = $this->getSecret();
		list($userID) = explode(':', (openssl_decrypt(base64_decode($rememberToken), $method, $key, OPENSSL_RAW_DATA, $iv)));
		// 校验
		$user = (new User())->findBy($userID);
		Session::set('user', $user);
		return $user->remember_token == $rememberToken;
	}

	/**
	 * 加密
	 *
	 * @return array
	 */
	protected function getSecret()
	{
		return ['admin_auth', 'AES-128-CBC', '1234567890123412'];
	}

	/**
	 * 记住
	 *
	 * @param $user
	 * @return void
	 */
	protected function LoginRemember($user, Request $request)
	{
		if ($request->has('remember')) {
			$rememberToken = $this->secretRememberToken($user->id, $this->generateRememberToken());
			$user->remember_token = $rememberToken;
			Cookie::forever('remember_token', $rememberToken);
		}
	}

}