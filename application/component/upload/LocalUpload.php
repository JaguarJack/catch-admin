<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16 0016
 * Time: 下午 14:51
 */
namespace app\component\upload;

use think\exception\ThrowableError;
use think\facade\Request;
use app\exceptions\UploadException;

class LocalUpload implements UploadInterface
{
	protected $name = null;

	/**
	 * Upload File
	 *
	 * @time at 2018年11月16日
	 * @return string
	 */
	public function file(){}

	/**
	 * Upload Image
	 *
	 * @time at 2018年11月16日
	 * @return string
	 */
	public function image()
	{
		try {
			$file = Request::file($this->name);
			if (!$this->name) {
				throw new UploadException('请选择上传的图片');
			}
			$info = $file->validate(config('admin.image'))->move(config('admin.local_upload_path'));
			if (!$info) {
				throw new UploadException($file->getError());
			}
			return $info->getSaveName();
		} catch (UploadException $exception) {
			return $exception->getMessage();
		}
	}
	/**
	 * Set Image Name
	 *
	 * @time at 2018年11月16日
	 * @param $name
	 * @return $this
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}
}