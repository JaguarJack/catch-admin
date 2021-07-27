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

class ApiTesterUserenvSeed extends Seeder
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
    'env_name' => 'localhost',
    'appid' => 'wx407e4',
    'project_id' => '1',
    'env_json' => '{"{{host}}":"http://127.0.0.1:8000","{{status}}":"5","{{appid}}":"wx407","{{authorization}}":"Bearer{{手动替换为login接口的token}}"}',
    'selected' => 1,
    'created_at' => 1622029539,
    'updated_at' => 1622386890,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
  1 => 
  array (
    'id' => 2,
    'env_name' => 'api.server.local',
    'appid' => 'wx407',
    'project_id' => '1',
    'env_json' => '{"{{host}}":"http://api.server.local"}',
    'selected' => 0,
    'created_at' => 1622030904,
    'updated_at' => 1622386890,
    'deleted_at' => 0,
    'creator_id' => 1,
  ),
);

        foreach ($data as $item) {
            \catchAdmin\apimanager\model\ApiTesterUserenv::create($item);
        }
    }
}