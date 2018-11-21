<?php

use think\migration\Seeder;

class Roles extends Seeder
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
    		'name' => '超级管理员',
	    ];

	    $this->table(config('permissions.table.role'))->insert($data)->save();
    }
}