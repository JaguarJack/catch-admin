<?php
/**
 * UserRequest.php
 * Created by wuyanwen <wuyanwen1992@gmail.com>
 * Date: 2018/11/29 0029 21:56
 */
namespace app\admin\request;

use think\Request;
use think\Container;
use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;

abstract class FormRequest extends Request
{
	/**
	 * FormRequest constructor.
	 * @throws \think\Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$err = $this->validate();

		return $this->error($err);
	}

	protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
	{
		$type = $this->getResponseType();
		if (is_null($url)) {
			$url = $this->isAjax() ? '' : 'javascript:history.back(-1);';
		} elseif ('' !== $url) {
			$url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : url($url);
		}

		$result = [
			'code' => 0,
			'msg'  => $msg,
			'data' => $data,
			'url'  => $url,
			'wait' => $wait,
		];

		if ('html' == strtolower($type)) {
			$type = 'jump';
		}

		$response = Response::create($result, $type)->header($header)->options(['jump_template' => config('dispatch_error_tmpl')]);

		throw new HttpResponseException($response);
	}

	/**
	 * 获取当前的response 输出类型
	 * @access protected
	 * @return string
	 */
	protected function getResponseType()
	{
		return !$this->isAjax()
			? config('default_ajax_return')
			: config('default_return_type');
	}
}