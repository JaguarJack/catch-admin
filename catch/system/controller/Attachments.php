<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catchAdmin\system\model\Attachments as AttachmentsModel;
use catcher\Utils;
use think\facade\Filesystem;

class Attachments extends CatchController
{

    public function index(AttachmentsModel $model)
    {
        return CatchResponse::paginate($model->getList());
    }

    public function delete($id, AttachmentsModel $model)
    {
        $ids = Utils::stringToArrayBy($id);

        foreach ($ids as $id) {
            $attachment = $model->findBy($id);
            if ($attachment && $model->deleteBy($id)) {
                Filesystem::delete($attachment->path);
            }
        }

        return CatchResponse::success();
    }
}
