<?php

namespace Catch\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class User
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Authenticatable|Model $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Authenticatable|Model $user)
    {
        //
        $this->user = $user;
    }
}
