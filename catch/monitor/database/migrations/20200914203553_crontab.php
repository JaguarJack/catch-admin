<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Crontab extends Migrator
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
        $table = $this->table('crontab', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '定时任务' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => false,'comment' => '任务名称',])
			->addColumn('group', 'boolean', ['null' => false,'default' => 1,'signed' => false,'comment' => '1 默认 2 系统',])
			->addColumn('task', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => false,'comment' => '任务名称',])
			->addColumn('cron', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => 'cron 表达式',])
			->addColumn('tactics', 'boolean', ['null' => false,'default' => 1,'signed' => false,'comment' => '1 立即执行 2 执行一次 3 正常执行',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => false,'comment' => '1 正常 2 禁用',])
			->addColumn('remark', 'string', ['limit' => 1000,'null' => false,'default' => '','signed' => false,'comment' => '备注',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}
