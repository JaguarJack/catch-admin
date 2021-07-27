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

use think\migration\Seeder;

class ApiCategorySeed extends Seeder
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
       $data = array (
  0 => 
  array (
    'id' => 1,
    'category_title' => '微信第三方平台',
    'parent_id' => 0,
    'category_name' => 'wechatopen',
    'status' => 1,
    'sort' => 1,
    'created_at' => 1621414770,
    'updated_at' => 1621414770,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
  1 => 
  array (
    'id' => 2,
    'category_title' => '微信交易组件标准版',
    'parent_id' => 1,
    'category_name' => 'MiniShop_Base',
    'status' => 1,
    'sort' => 1,
    'created_at' => 1621415897,
    'updated_at' => 1621415897,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
  2 => 
  array (
    'id' => 3,
    'category_title' => '腾讯AI开放平台',
    'parent_id' => 0,
    'category_name' => 'tencentAI',
    'status' => 1,
    'sort' => 1,
    'created_at' => 1621493345,
    'updated_at' => 1621493345,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
  3 => 
  array (
    'id' => 4,
    'category_title' => '批量代云开发',
    'parent_id' => 1,
    'category_name' => 'componenttcb',
    'status' => 1,
    'sort' => 1,
    'created_at' => 1621494287,
    'updated_at' => 1621494287,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
  4 => 
  array (
    'id' => 5,
    'category_title' => '本地接口',
    'parent_id' => 0,
    'category_name' => 'local',
    'status' => 1,
    'sort' => 2,
    'created_at' => 1621494287,
    'updated_at' => 1621494287,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
);

        foreach ($data as $item) {
            \catchAdmin\apimanager\model\ApiCategory::create($item);
        }
    }
}