<?php

use think\migration\Seeder;

class DevelopersSeed extends Seeder
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
        return \catchAdmin\system\model\Developers::create([
            'username' => 'eleven',
            'password' => '123456',
            'status' => 1,
        ]);
    }
}
