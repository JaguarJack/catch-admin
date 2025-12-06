<?php

namespace Modules\System\Listeners;

use Modules\Common\Events\UploadedEvent;
use Modules\System\Models\SystemAttachments;

class UploadedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UploadedEvent $event
     * @return void
     */
    public function handle(UploadedEvent $event): void
    {
        $info = [
            'filename' => $event->uploadInfo['original_name'],
            'path' => $event->uploadInfo['path'],
            'filesize' => $event->uploadInfo['size'],
            'mimetype' => $event->uploadInfo['type'],
            'extension' => $event->uploadInfo['ext'],
            'driver' => $event->uploadInfo['driver'],
            'category_id' => $event->uploadInfo['category_id'],
        ];

        app(SystemAttachments::class)->storeBy($info);
    }
}
