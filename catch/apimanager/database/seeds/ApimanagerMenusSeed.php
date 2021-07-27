<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

use think\facade\Log;
use think\migration\Seeder;

class ApimanagerMenusSeed extends Seeder
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
        Log::write("ApimanagerMenusSeed",'debug');
        \catcher\Utils::importTreeData($this->getPermissions(), 'permissions', 'parent_id');
    }

    protected function getPermissions()
    {
       return array (
  0 => 
  array (
    'id' => 136,
    'permission_name' => 'API管理',
    'parent_id' => 0,
    'level' => '',
    'route' => '/apimanager',
    'icon' => 'el-icon-sort',
    'module' => 'apimanager',
    'creator_id' => 1,
    'permission_mark' => 'apimanager',
    'component' => 'layout',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 10,
    'created_at' => 1622926698,
    'updated_at' => 1622959419,
    'deleted_at' => 0,
    'children' => 
    array (
      0 => 
      array (
        'id' => 137,
        'permission_name' => 'API分类',
        'parent_id' => 136,
        'level' => '',
        'route' => '/apicategory',
        'icon' => 'el-icon-s-grid',
        'module' => 'apimanager',
        'creator_id' => 1,
        'permission_mark' => 'apicategory',
        'component' => 'apicategory',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 10,
        'created_at' => 1622928640,
        'updated_at' => 1622959419,
        'deleted_at' => 0,
      ),
      1 => 
      array (
        'id' => 138,
        'permission_name' => 'API环境变量',
        'parent_id' => 136,
        'level' => '',
        'route' => '/apienv',
        'icon' => 'el-icon-setting',
        'module' => 'apimanager',
        'creator_id' => 1,
        'permission_mark' => 'apienv',
        'component' => 'apienv',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 9,
        'created_at' => 1622930243,
        'updated_at' => 1622959419,
        'deleted_at' => 0,
      ),
      2 => 
      array (
        'id' => 140,
        'permission_name' => 'API测试列表',
        'parent_id' => 136,
        'level' => '',
        'route' => '/apitester',
        'icon' => 'el-icon-stopwatch',
        'module' => 'apimanager',
        'creator_id' => 1,
        'permission_mark' => 'apitester',
        'component' => 'apitester',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 1,
        'sort' => 8,
        'created_at' => 1622951640,
        'updated_at' => 1622959419,
        'deleted_at' => 0,
      ),
      3 => 
      array (
        'id' => 141,
        'permission_name' => 'API运行',
        'parent_id' => 136,
        'level' => '',
        'route' => '/apimanager/apirun',
        'icon' => 'el-icon-position',
        'module' => 'apimanager',
        'creator_id' => 1,
        'permission_mark' => 'apirun',
        'component' => 'apirun',
        'redirect' => '',
        'keepalive' => 1,
        'type' => 1,
        'hidden' => 2,
        'sort' => 1,
        'created_at' => 1622951894,
        'updated_at' => 1622959419,
        'deleted_at' => 0,
      ),
    ),
  ),
);
    }
}
