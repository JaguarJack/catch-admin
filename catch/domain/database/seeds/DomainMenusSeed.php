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

class DomainMenusSeed extends Seeder
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
    'id' => 82,
    'permission_name' => '域名管理',
    'parent_id' => 0,
    'level' => '',
    'route' => '/domain',
    'icon' => 'el-icon-stopwatch',
    'module' => 'domain',
    'creator_id' => 1,
    'permission_mark' => 'domain',
    'component' => 'layout',
    'redirect' => '/domain/index',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 1,
    'created_at' => 1601105834,
    'updated_at' => 1612754299,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 83,
        'permission_name' => '域名设置',
        'parent_id' => 82,
        'level' => '82',
        'route' => '/domain/index',
        'icon' => 'el-icon-setting',
        'module' => 'domain',
        'creator_id' => 1,
        'permission_mark' => 'domain',
        'component' => 'domain',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 8,
        'created_at' => 1601105879,
        'updated_at' => 1612754299,
        'deleted_at' => 0,
      ),
      1 => 
      array (
        'id' => 84,
        'permission_name' => '域名记录',
        'parent_id' => 82,
        'level' => '82',
        'route' => '/domain/record/:domain',
        'icon' => 'el-icon-document',
        'module' => 'domain',
        'creator_id' => 1,
        'permission_mark' => 'domainRecord',
        'component' => 'domainRecord',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 2,
        'sort' => 1,
        'created_at' => 1601112569,
        'updated_at' => 1612754299,
        'deleted_at' => 0,
      ),
    ),
  ),
);
    }
}
