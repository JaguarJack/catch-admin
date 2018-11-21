<?php

use think\migration\Seeder;

class UserHasRoles extends Seeder
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
    	    'uid'     => 1,
		    'role_id' => 1,
	    ];

	    $this->table(config('permissions.table.user_has_roles'))->insert($data)->save();
    }
}