<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catchAdmin\system\model\Attachments as AttachmentsModel;

class Attachments extends CatchController
{
    /**
     * 列表
     *
     * @time 2020年07月25日
     * @param AttachmentsModel $model
     * @return \think\response\Json
     */
    public function index(AttachmentsModel $model)
    {
        return CatchResponse::paginate($model->getList());
    }

    /**
     * 删除
     *
     * @time 2020年07月25日
     * @param $id
     * @param AttachmentsModel $model
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\response\Json
     */
    public function delete($id, AttachmentsModel $model)
    {
        return CatchResponse::success($model->deletes($id));
    }
}
