<?php

use think\migration\Seeder;

class UserSeeder extends Seeder
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
        $row  = [
            'username'          => 'wuyanwen',
            'password'          => password_hash('password',PASSWORD_DEFAULT),
            'email'             => 'njphper@gmail.com',
            'status'            => 1,
            'last_login_ip'     => ip2long('127.0.0.1'),
            'last_login_time'   => time(),
            'created_at'        => time(),
            'updated_at'        => time(),
            'deleted_at'        => '',
        ];
        $this->table('users')->insert($row)->save();
    }
}