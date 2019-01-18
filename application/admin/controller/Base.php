<?php

namespace app\admin\controller;

use think\Controller;
use app\traits\ControllerTrait;

abstract class Base extends Controller
{
	use ControllerTrait;

	protected $limit = 10;

	protected $page  = 1;

	protected $middleware = ['checkLogin', 'auth', 'logRecord'];

	/**
	 * 过滤参数
	 *
	 * @time at 2018年11月15日
	 * @param $params
	 * @return void
	 */
	protected function checkParams(&$params)
	{
		$this->limit = $params['limit'] ?? $this->limit;
		$this->page  = $params['page'] ?? $this->page;

		foreach ($params as $key => $param) {
			if (!$param || $key == 'limit' || $key == 'page') {
				unset($params[$key]);
			}
		}
		$this->start = $this->start();
	}

	/**
	 * Table ID Start
	 *
	 * @time at 2018年11月16日
	 * @return float|int
	 */
	protected function start()
	{
		return (int)$this->limit * ((int)$this->page - 1) + 1;
	}
}
