<?php
declare (strict_types = 1);

namespace catcher\command;

use catcher\CatchAdmin;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class BackupCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('backup:data')
            ->addArgument('tables', Argument::REQUIRED, 'backup tables')
            ->addOption('zip', 'z',Option::VALUE_REQUIRED, 'is need zip')
            ->setDescription('backup data you need');
    }

    protected function execute(Input $input, Output $output)
    {
        $tables = $this->input->getArgument('tables');

        $isZip = $this->input->getOption('zip') ?? true;

        $this->generator(explode(',', $tables), CatchAdmin::backupDirectory());

        if ($isZip) {
            $this->zip();
        }

        $output->info('succeed!');
    }


    /**
     *
     * @time 2019年09月30日
     * @param $tables
     * @param $format
     * @param $path
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function generator($tables, $path): void
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
     * @param $format
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
     * @param $format
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
     * @param $data
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

    protected function zip()
    {
        if (extension_loaded('zip')) {
            $files = glob(CatchAdmin::backupDirectory() . '*.sql');
            $zip = new \ZipArchive();
            $backupPath = runtime_path('database/');
            CatchAdmin::makeDirectory($backupPath);
            $zip->open($backupPath . 'backup.zip', \ZipArchive::CREATE);
            $zip->addEmptyDir('backup');
            foreach ($files as $file) {
                $zip->addFile($file, 'backup/'. basename($file));
            }
            $zip->close();

            foreach ($files as $file) {
                unlink($file);
            }
            rmdir(CatchAdmin::backupDirectory());
        }
    }
}
