<?php

namespace Modules\Develop\Listeners;

use Catch\Events\Module\Created;
use Modules\Develop\Support\Generate\Module;

class CreatedListener
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
     * @param Created $event
     * @return void
     */
    public function handle(Created $event): void
    {
        $module = $event->module;

        (new Module(
            $module['path'],
            $module['dirs']['controllers'],
            $module['dirs']['models'],
            $module['dirs']['requests'],
            $module['dirs']['database']
        )
        )->create();
    }
}
