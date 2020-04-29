<?php

use think\migration\Seeder;

class DepartmentsSeed extends Seeder
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
            'id' => 1,
            'department_name' => '总部',
            'parent_id' => 0,
            ],
            [
                'id' => 2,
                'department_name' => '北京总部',
                'parent_id' => 1,
            ],
            [
                'id' => 3,
                'department_name' => '南京总部',
                'parent_id' => 1,
            ],
        ];

        foreach ($data as $item) {
            \catchAdmin\permissions\model\Department::create($item);
        }
    }
}
