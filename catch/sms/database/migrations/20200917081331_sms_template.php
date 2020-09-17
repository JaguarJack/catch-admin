<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class SmsTemplate extends Migrator
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
        $table = $this->table('sms_template', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '短信模版' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('operator', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => '运营商',])
            ->addColumn('name', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => '模版名称',])
			->addColumn('identify', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => '模版标识',])
			->addColumn('code', 'string', ['limit' => 100,'null' => false,'default' => '','signed' => false,'comment' => '模版CODE',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}
