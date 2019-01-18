<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Log extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $table = $this->table('option_log', ['engine' => 'InnoDB', 'comment' => '操作日志表']);
        $table->addColumn('user_name', 'string',['limit' => 50, 'default'=>'','comment'=>'用户名'])
            ->addColumn('user_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => '用户ID'])
            ->addColumn('module', 'string',['limit' => 20, 'default'=>'','comment'=>'模块'])
            ->addColumn('controller', 'string',['limit' => 20, 'default'=>'','comment'=>'控制器'])
            ->addColumn('action', 'string',['limit' => 20, 'default'=>'','comment'=>'方法'])
            ->addColumn('option', 'string',['limit' => 50, 'default'=>'','comment'=>'操作'])
            ->addColumn('method', 'string',['limit' => 15, 'default'=>'','comment'=>'请求方法'])
            ->addColumn('created_at', 'timestamp', [ 'default' => 'CURRENT_TIMESTAMP','comment' => '更新时间'])
            ->create();
    }
}
