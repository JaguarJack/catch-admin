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

class MemberMenusSeed extends Seeder
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
    'id' => 78,
    'permission_name' => '会员管理',
    'route' => '/member',
    'url' => '',
    'component' => 'layout',
    'parent_id' => 0,
    'level' => '',
    'icon' => 'el-icon-user-solid',
    'module' => 'member',
    'creator_id' => 1,
    'permission_mark' => 'member',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 999,
    'created_at' => 1606482172,
    'updated_at' => 1606482427,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 84,
        'permission_name' => '会员管理',
        'route' => '/member/member',
        'url' => '/member/member',
        'component' => 'formTable',
        'parent_id' => 78,
        'level' => '78',
        'icon' => '',
        'module' => 'member',
        'creator_id' => 1,
        'permission_mark' => 'member',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606483741,
        'updated_at' => 1606567162,
        'deleted_at' => 0,
      ),
      1 => 
      array (
        'id' => 85,
        'permission_name' => '会员等级',
        'route' => '/member/level',
        'url' => '/member/level',
        'component' => 'formTable',
        'parent_id' => 78,
        'level' => '78',
        'icon' => '',
        'module' => 'member',
        'creator_id' => 1,
        'permission_mark' => 'level',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606483783,
        'updated_at' => 1606486532,
        'deleted_at' => 0,
      ),
      2 => 
      array (
        'id' => 89,
        'permission_name' => '内部会员',
        'route' => '/member/insid',
        'url' => '/member/insid',
        'component' => 'formTable',
        'parent_id' => 78,
        'level' => '78',
        'icon' => '',
        'module' => 'member',
        'creator_id' => 1,
        'permission_mark' => 'inside',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606484253,
        'updated_at' => 1606567146,
        'deleted_at' => 0,
      ),
      3 => 
      array (
        'id' => 90,
        'permission_name' => '站内消息',
        'route' => '/member/message',
        'url' => '/member/message',
        'component' => 'formTable',
        'parent_id' => 78,
        'level' => '78',
        'icon' => '',
        'module' => 'member',
        'creator_id' => 1,
        'permission_mark' => 'message',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606484309,
        'updated_at' => 1606484309,
        'deleted_at' => 0,
      ),
      4 => 
      array (
        'id' => 92,
        'permission_name' => '会员结构',
        'route' => '/member/tree',
        'url' => '/member/tree',
        'component' => 'formTable',
        'parent_id' => 78,
        'level' => '78',
        'icon' => '',
        'module' => 'member',
        'creator_id' => 1,
        'permission_mark' => 'tree',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606484451,
        'updated_at' => 1606567134,
        'deleted_at' => 0,
      ),
    ),
  ),
);
    }
}
