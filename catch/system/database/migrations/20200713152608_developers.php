<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Developers extends Migrator
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
        $table = $this->table('developers', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '开发者' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('username', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => '用户名',])
			->addColumn('password', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => false,'comment' => '密码',])
			->addColumn('mobile', 'string', ['limit' => 30,'null' => false,'default' => '','signed' => false,'comment' => '手机号',])
			->addColumn('id_card', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => false,'comment' => '身份证',])
			->addColumn('alipay_account', 'string', ['limit' => 100,'null' => false,'default' => '','signed' => false,'comment' => '支付宝账户',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => false,'comment' => '1 待认证 2 已认证',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}
