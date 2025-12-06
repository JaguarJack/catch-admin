<?php

namespace Modules\User\Models\Observers;

use Modules\User\Models\User;

class UsersObserver
{
    public function created(User $user): void
    {
        //
    }

    public function updated(User $user): void
    {
        //
    }

    public function deleted(User $user): void
    {
        //
    }
}
