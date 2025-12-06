<?php

use Illuminate\Database\Seeder;
use Modules\User\Models\User;

return new class extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        $users = [[
            'username' => 'catchadmin',
            'email' => 'catch@admin.com',
            'password' => 'catchadmin',
            'creator_id' => 1,
            'department_id' => 0,
        ]];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            $user = new User([
                'username' => 'catchadmin',
                'email' => 'catch@admin.com',
                'password' => 'catchadmin',
                'creator_id' => 1,
                'department_id' => 0,
            ]);

            $user->save();
        }
    }
};
