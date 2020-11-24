<?php
/**
 * @filename  Upload.php
 * @createdAt 2020/1/25
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catchAdmin\system\controller;

use catchAdmin\system\model\Attachments;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;
use catcher\CatchUpload;
use catcher\exceptions\FailedException;

class Upload extends CatchController
{
    protected $attachment;

    public function __construct(Attachments $attachment)
    {
        $this->attachment = $attachment;
    }

  /**
   * image upload
   *
   * @time 2020年01月25日
   * @param CatchRequest $request
   * @param CatchUpload $upload
   * @return \think\response\Json
   */
    public function image(CatchRequest $request, CatchUpload $upload): \think\response\Json
    {
        $images = $request->file();

        if (!$images) {
            throw new FailedException('请选择图片上传');
        }

        return CatchResponse::success($upload->checkImages($images)->multiUpload($images['image']));
    }

  /**
   * file upload
   *
   * @time 2020年01月25日
   * @param CatchRequest $request
   * @param CatchUpload $upload
   * @return \think\response\Json
   */
    public function file(CatchRequest $request, CatchUpload $upload): \think\response\Json
    {
        $files = $request->file();

        return CatchResponse::success($upload->checkFiles($files)->multiUpload($files['file']));
    }
}
