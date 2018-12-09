<?php

use think\migration\Seeder;

class Users extends Seeder
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
    	$data = [
    		'name' => 'admin',
		    'email' => 'admin@gmail.com',
		    'password' => password_hash('admin', PASSWORD_DEFAULT),
			'created_at' => date('Y-m-d H:i:s'),
			'login_at' => date('Y-m-d H:i:s'),
	    ];

    	$this->table('users')->insert([$data])->save();
    }
}