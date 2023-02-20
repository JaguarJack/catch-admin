<?php

use Illuminate\Database\Seeder;
use Modules\User\Models\User;

return new class extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        $user = new User([
            'username' => 'catchadmin',

            'email' => 'catch@admin.com',

            'password' => 'catchadmin',

            'creator_id' => 1,

            'department_id' => 0
        ]);

        $user->save();
    }
};
