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

class OrderMenusSeed extends Seeder
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
    'id' => 77,
    'permission_name' => '任务管理',
    'route' => '/trade/task',
    'url' => '',
    'component' => 'layout',
    'parent_id' => 0,
    'level' => '',
    'icon' => 'el-icon-s-cooperation',
    'module' => 'order',
    'creator_id' => 1,
    'permission_mark' => 'order',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 899,
    'created_at' => 1606482068,
    'updated_at' => 1606482536,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 82,
        'permission_name' => '任务商品',
        'route' => '/trade/goods',
        'url' => '/trade/goods',
        'component' => 'formTable',
        'parent_id' => 77,
        'level' => '77',
        'icon' => '',
        'module' => 'order',
        'creator_id' => 1,
        'permission_mark' => 'tradeGoods',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606483474,
        'updated_at' => 1606483860,
        'deleted_at' => 0,
      ),
      1 => 
      array (
        'id' => 83,
        'permission_name' => '任务订单',
        'route' => '/trade/order',
        'url' => '/trade/order',
        'component' => 'formTable',
        'parent_id' => 77,
        'level' => '77',
        'icon' => '',
        'module' => 'order',
        'creator_id' => 1,
        'permission_mark' => 'tradeOrder',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1606483528,
        'updated_at' => 1606483846,
        'deleted_at' => 0,
      ),
    ),
  ),
);
    }
}
