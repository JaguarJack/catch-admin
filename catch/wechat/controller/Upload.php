<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\wechat\controller;

use catchAdmin\system\model\Attachments;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;
use catcher\CatchUpload;

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
     * @throws \Exception
     */
    public function image(CatchRequest $request, CatchUpload $upload): \think\response\Json
    {
        $images = $request->file();

        return CatchResponse::success($upload->setDriver(CatchUpload::LOCAL)->checkImages($images)->multiUpload($images['image']));
    }

    /**
     * file upload
     *
     * @time 2020年01月25日
     * @param CatchRequest $request
     * @param CatchUpload $upload
     * @return \think\response\Json
     * @throws \Exception
     */
    public function file(CatchRequest $request, CatchUpload $upload): \think\response\Json
    {
        $files = $request->file();

        return CatchResponse::success($upload->setDriver(CatchUpload::LOCAL)->checkFiles($files)->multiUpload($files['file']));
    }
}