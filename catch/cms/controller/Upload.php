<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\controller;

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

        return CatchResponse::success([
            'filePath' => $upload->checkImages($images)->multiUpload($images['image'])
        ]);
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

        return CatchResponse::success([
            'src' => $upload->checkFiles($files)->multiUpload($files['file'])
        ]);
    }
}
