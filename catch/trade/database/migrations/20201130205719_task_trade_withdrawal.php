<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class TaskTradeWithdrawal extends Migrator
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
        $table = $this->table('trade_withdrawal', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_0900_ai_ci', 'comment' => '会员提现流水' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('operate_id', 'char', ['limit' => 10,'null' => true,'signed' => true,'comment' => '操作者ID',])
			->addColumn('nick_name', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '会员昵称',])
			->addColumn('collect_account', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '收款账号',])
			->addColumn('account_name', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '账号名称',])
			->addColumn('account_type', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '账号类型 1支付宝 2微信 3银行卡 4其他',])
			->addColumn('money', 'decimal', ['precision' => 11,'scale' => 0,'null' => false,'default' => 0,'signed' => true,'comment' => '提现金额',])
			->addColumn('real_name', 'char', ['limit' => 16,'null' => false,'default' => 0,'signed' => true,'comment' => '真实姓名',])
			->addColumn('member_mobile', 'char', ['limit' => 11,'null' => false,'default' => 0,'signed' => true,'comment' => '提现人手机号',])
			->addColumn('remark', 'char', ['limit' => 64,'null' => true,'signed' => true,'comment' => '备注',])
			->addColumn('order_id', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '订单号',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '处理状态',])
			->addColumn('member_id', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '会员ID',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
