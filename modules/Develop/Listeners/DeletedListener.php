<?php

namespace Modules\Develop\Listeners;

use Catch\CatchAdmin;
use Catch\Events\Module\Deleted;

class DeletedListener
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
     *
     *
     * @param Deleted $event
     * @return void
     */
    public function handle(Deleted $event): void
    {
        CatchAdmin::deleteModulePath($event->module['path']);
    }
}
