<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

use think\migration\Seeder;

class SmsMenusSeed extends Seeder
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
        \catcher\Utils::importTreeData($this->getPermissions(), 'permissions', 'parent_id');
    }

    protected function getPermissions()
    {
       return array (
  0 => 
  array (
    'id' => 113,
    'permission_name' => '短信管理',
    'parent_id' => 0,
    'level' => '',
    'route' => '/sms',
    'icon' => 'el-icon-s-promotion',
    'module' => 'sms',
    'creator_id' => 1,
    'permission_mark' => 'sms',
    'component' => 'layout',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 1,
    'created_at' => 1600229598,
    'updated_at' => 1600229598,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 114,
        'permission_name' => '短信配置',
        'parent_id' => 113,
        'level' => '113',
        'route' => '/sms/config',
        'icon' => 'el-icon-copy-document',
        'module' => 'sms',
        'creator_id' => 1,
        'permission_mark' => 'sms',
        'component' => 'sms',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1600229654,
        'updated_at' => 1600229778,
        'deleted_at' => 0,
      ),
    ),
  ),
);
    }
}
