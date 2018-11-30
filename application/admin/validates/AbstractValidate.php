<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 下午 16:31
 */
namespace app\admin\validates;;

use think\Validate;

abstract class AbstractValidate extends Validate
{

	/**
	 * Get Validate Errors
	 *
	 * @time at 2018年11月12日
	 * @param $data
	 * @return array
	 */
	public function getErrors($data)
	{
		$this->check($data);

		return $this->getError();
	}


	public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->rule[$name] = $value;
    }
}