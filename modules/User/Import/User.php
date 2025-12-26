<?php

namespace Modules\User\Import;

use Catch\Support\Excel\Import;
use Illuminate\Support\Collection;
use Modules\User\Models\User as UserModel;

class User extends Import
{
    public function collection(Collection $users): void
    {
        // TODO: Implement collection() method.
        $users->each(function ($user) {
            $userModel = new UserModel();
            $userModel->username = $user[1];
            $userModel->email = $user[2];
            $userModel->password = $user[3];
            $userModel->save();
        });
    }
}
