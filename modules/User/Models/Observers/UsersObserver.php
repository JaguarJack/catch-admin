<?php

namespace Modules\User\Models\Observers;

use Modules\User\Models\User;

class UsersObserver
{
    /**
     *
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        //
    }

    /**
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        //
    }
}
