<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 上午 11:43
 */
namespace app\traits;

use think\facade\Session;
use app\component\upload\UploadInterface;
use app\component\upload\LocalUpload;

trait ControllerTrait
{
	protected $vars = [];

	/**
	 * 绑定实现
	 *
	 * @time at 2018年11月16日
	 * @return void
	 */
	public function initialize()
	{
		bind(UploadInterface::class, LocalUpload::class);
	}

	/**
	 * 是否登录
	 *
	 * @time at 2018年11月15日
	 * @return bool
	 */
	protected function isLogin()
	{
		return $this->getLoginUser() ? true : false;
	}

	/**
	 * 获取登录用户
	 *
	 * @time at 2018年11月15日
	 * @return mixed
	 */
	protected function getLoginUser()
	{
		return Session::get('user');
	}

	/**
	 * fetch 重写
	 *
	 * @time at 2018年11月15日
	 * @param string $template
	 * @param array $vars
	 * @param array $config
	 * @return mixed
	 */
	protected function fetch($template = '', $vars = [], $config = [])
	{
		$vars = array_merge($this->vars, $vars);

		return $this->view->fetch($template, $vars, $config);
	}

	/**
	 * Set Template Vars
	 *
	 * @time at 2018年11月12日
	 * @param $name
	 * @param $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		// TODO: Implement __set() method.
		$this->vars[$name] = $value;
	}
}