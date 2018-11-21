<?php

use think\migration\Seeder;

class RoleHasPermissions extends Seeder
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
		$p = \think\permissions\facade\Permissions::all();
		$data = [];
		foreach ($p as $v) {
			$data[] = [
				'role_id' => 1,
				'permission_id' => $v->id,
			];
		}

	    $this->table(config('permissions.table.role_has_permissions'))->insert($data)->save();
    }
}