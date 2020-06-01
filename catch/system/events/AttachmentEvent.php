<?php
namespace catchAdmin\system\events;


use catchAdmin\system\model\Attachments;

class AttachmentEvent
{
    public function handle($params)
    {
        $file = $params['file'];

        unset($params['file']);

        Attachments::store($params, $file);
    }
}