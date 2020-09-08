<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
use think\migration\Seeder;

class RolesSeed extends Seeder
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
        \catchAdmin\permissions\model\Roles::create([
            'role_name' => '超级管理员',
            'identify'  => 'admin',
            'description' => 'super user',
            'data_range' => 1,
            'creator_id' => 1,
        ]);

        \think\facade\Db::name( 'user_has_roles')->insert([
            'role_id' => 1,
            'uid' => 1,
        ]);
    }
}