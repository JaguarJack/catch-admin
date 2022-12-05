<?php

namespace App\Listeners;

class RouteMatched
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
     * @param  object  $event
     * @return void
     */
    public function handle(\Illuminate\Routing\Events\RouteMatched $event)
    {
        //
        // dd($event->route);
    }
}
