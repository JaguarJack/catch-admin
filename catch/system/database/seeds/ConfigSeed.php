<?php

use think\migration\Seeder;

class ConfigSeed extends Seeder
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
        $data = [
            [
                'name' => '基础配置',
                'pid' => 0,
                'component' => 'basic',
                'key' => 'basic',
            ],
            [
                'name' => '上传配置',
                'pid' => 0,
                'component' => 'upload',
                'key' => 'upload',
            ],
        ];

        foreach ($data as $item) {
            \catchAdmin\system\model\Config::create($item);
        }
    }
}
