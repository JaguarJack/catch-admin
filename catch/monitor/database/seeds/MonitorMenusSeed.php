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

class MonitorMenusSeed extends Seeder
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
    'id' => 103,
    'permission_name' => '系统监控',
    'parent_id' => 0,
    'level' => '',
    'route' => '/monitor',
    'icon' => 'el-icon-view',
    'module' => 'monitor',
    'creator_id' => 1,
    'permission_mark' => 'monitor',
    'component' => 'layout',
    'redirect' => '/monitor/crontab',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 1,
    'created_at' => 1600126383,
    'updated_at' => 1600136975,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 104,
        'permission_name' => '定时任务',
        'parent_id' => 103,
        'level' => '103',
        'route' => '/monitor/crontab',
        'icon' => 'el-icon-time',
        'module' => 'monitor',
        'creator_id' => 1,
        'permission_mark' => 'crontab',
        'component' => 'crontab',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 1,
        'created_at' => 1600126931,
        'updated_at' => 1600136975,
        'deleted_at' => 0,
        'children' => 
        array (
          0 => 
          array (
            'id' => 105,
            'permission_name' => '列表',
            'parent_id' => 104,
            'level' => '103-104',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'crontab@index',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600127069,
            'updated_at' => 1600136975,
            'deleted_at' => 0,
          ),
          1 => 
          array (
            'id' => 106,
            'permission_name' => '保存',
            'parent_id' => 104,
            'level' => '103-104',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'crontab@save',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600127078,
            'updated_at' => 1600136975,
            'deleted_at' => 0,
          ),
          2 => 
          array (
            'id' => 107,
            'permission_name' => '更新',
            'parent_id' => 104,
            'level' => '103-104',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'crontab@update',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600127085,
            'updated_at' => 1600136975,
            'deleted_at' => 0,
          ),
          3 => 
          array (
            'id' => 108,
            'permission_name' => '删除',
            'parent_id' => 104,
            'level' => '103-104',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'crontab@delete',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600127091,
            'updated_at' => 1600136975,
            'deleted_at' => 0,
          ),
          4 => 
          array (
            'id' => 109,
            'permission_name' => '禁用/启用',
            'parent_id' => 104,
            'level' => '103-104',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'crontab@disOrEnable',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600129279,
            'updated_at' => 1600136975,
            'deleted_at' => 0,
          ),
        ),
      ),
      1 => 
      array (
        'id' => 110,
        'permission_name' => '任务日志',
        'parent_id' => 103,
        'level' => '103-104',
        'route' => '/monitor/crontab/log/:crontab_id?',
        'icon' => 'el-icon-guide',
        'module' => 'monitor',
        'creator_id' => 1,
        'permission_mark' => 'CrontabLog',
        'component' => 'crontabLog',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 2,
        'sort' => 1,
        'created_at' => 1600167562,
        'updated_at' => 1600188651,
        'deleted_at' => 0,
        'children' => 
        array (
          0 => 
          array (
            'id' => 111,
            'permission_name' => '列表',
            'parent_id' => 110,
            'level' => '103-104-110',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'CrontabLog@index',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600167574,
            'updated_at' => 1600168082,
            'deleted_at' => 0,
          ),
          1 => 
          array (
            'id' => 112,
            'permission_name' => '删除',
            'parent_id' => 110,
            'level' => '103-104-110',
            'route' => '',
            'icon' => '',
            'module' => 'monitor',
            'creator_id' => 1,
            'permission_mark' => 'CrontabLog@delete',
            'component' => '',
            'redirect' => '',
            'keepalive' => 1,
            'type' => 2,
            'hidden' => 1,
            'sort' => 1,
            'created_at' => 1600167581,
            'updated_at' => 1600168082,
            'deleted_at' => 0,
          ),
        ),
      ),
    ),
  ),
);
    }
}
