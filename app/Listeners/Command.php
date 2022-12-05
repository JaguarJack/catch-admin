<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandFinished;

class Command
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
     * @param CommandFinished $event
     * @return void
     */
    public function handle(CommandFinished $event)
    {
        //
        // dd($event->command);
    }
}
