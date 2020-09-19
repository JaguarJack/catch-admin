<?php

use think\migration\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        return \catchAdmin\permissions\model\Users::create([
            'username' => 'admin',
            'password' => 'catchadmin',
            'email'    => 'catch@admin.com',
            'creator_id' => 1,
        ]);
    }
}
