<?php
/**
 * UserRequest.php
 * Created by wuyanwen <wuyanwen1992@gmail.com>
 * Date: 2018/11/29 0029 21:56
 */
namespace app\admin\request;

use think\Request;

abstract class FormRequest extends Request
{

    /**
     * FormRequest constructor.
     */
	public function __construct()
	{
		parent::__construct();

		if ($this->withServer($_SERVER)->isAjax(true) && $err = $this->validate()) {
            exit($this->error($err));
        }
	}

    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {

        return json([
            'code' => 0,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ])->send();
    }

}