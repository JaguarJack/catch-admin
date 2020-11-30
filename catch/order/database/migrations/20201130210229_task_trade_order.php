<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class TaskTradeOrder extends Migrator
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
        $table = $this->table('trade_order', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_0900_ai_ci', 'comment' => '任务订单表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('start_time', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '开始时间|date',])
			->addColumn('end_time', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '结束时间|date',])
			->addColumn('goods_id', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '商品ID|text',])
			->addColumn('review_remark', 'char', ['limit' => 255,'null' => false,'default' => 0,'signed' => true,'comment' => '审核意见|text',])
			->addColumn('fulfil_voucher', 'char', ['limit' => 255,'null' => false,'default' => 0,'signed' => true,'comment' => '完成凭证|image',])
			->addColumn('submit_remark', 'char', ['limit' => 255,'null' => false,'default' => 0,'signed' => true,'comment' => '提交备注|text',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间|text|||date',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '最近更新时间|text',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
			->addIndex(['end_time'], ['unique' => true,'name' => 'unique_end_time'])
			->addIndex(['review_remark'], ['unique' => true,'name' => 'unique_review_remark'])
            ->create();
    }
}
