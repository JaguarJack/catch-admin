<?php
declare(strict_types=1);

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library;

use catcher\CatchAdmin;
use catcher\facade\FileSystem;
use think\facade\Db;

class BackUpDatabase
{
    /**
     *
     * @time 2020年07月19日
     * @param $tables
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return void
     */
    public function done($tables)
    {
        $this->generator(explode(',', $tables));

        $this->zip();
    }

    /**
     *
     * @time 2019年09月30日
     * @param $tables
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    protected function generator($tables): void
    {
        foreach ($tables as $table) {
            $this->table = $table;

            $this->createDataFile();
        }
    }

    /**
     * 创建数据文件
     *
     * @time 2019年09月27日
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function createDataFile(): void
    {
        $file = CatchAdmin::backupDirectory() . $this->table . '.sql';

        $handle = fopen($file, 'wb+');

        fwrite($handle, $begin = "BEGIN;\r\n", \strlen($begin));
        $this->createClass($this->table, $handle);
        fwrite($handle, $end = 'COMMIT;', \strlen($end));

        fclose($handle);
    }

    /**
     * 创建了临时模型
     *
     * @time 2019年09月27日
     * @param $table
     * @param $handle
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    protected function createClass($table, $handle)
    {
        $this->setUnbuffered();

        // 防止 IO 多次写入
        $buffer = [];

        // 记录中记录
        $total = Db::table($table)->count();

        $limit = 1000;

        // 生成器减少内存
        while ($total > 0) {
            $items = Db::table($table)->limit($limit)->select();

            $this->writeIn($handle, $items);

            $total -= $limit;
        }
    }

    /**
     * sql 文件格式
     *
     * @time 2019年09月27日
     * @param $handle
     * @param $datas
     * @return void
     */
    protected function writeIn($handle, $datas)
    {
        $values = '';
        $sql = '';
        foreach ($datas as $data) {
            foreach ($data as $value) {
                $values .= sprintf("'%s'", $value) . ',';
            }

            $sql .= sprintf('INSERT INTO `%s` VALUE (%s);' . "\r\n", $this->table, rtrim($values, ','));
            $values = '';
        }

        fwrite($handle, $sql, strlen($sql));
    }


    /**
     * 设置未缓存模式
     *
     * @time 2019年09月29日
     * @return void
     */
    protected function setUnbuffered()
    {
        $connections = \config('database.connections');

        $connections['mysql']['params'] = [
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
        ];

        \config([
            'connections' => $connections,
        ],'database.connections');

    }

    /**
     * 文件压缩
     *
     * @time 2020年07月19日
     * @throws \Exception
     * @return void
     */
    protected function zip()
    {
        $files = FileSystem::allFiles(CatchAdmin::backupDirectory());

        $storePath = runtime_path('database/');

        if (!FileSystem::isDirectory($storePath)) {
            FileSystem::makeDirectory($storePath);
        }

        (new Zip)->make($storePath . 'backup.zip')->addFiles($files)->close();

        FileSystem::deleteDirectory(CatchAdmin::backupDirectory());
    }
}