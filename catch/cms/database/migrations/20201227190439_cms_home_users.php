<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsHomeUsers extends Migrator
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
        $table = $this->table('cms_users', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '用户表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('username', 'string', ['limit' => 100,'null' => true,'signed' => true,'comment' => '用户名',])
			->addColumn('email', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '邮箱',])
			->addColumn('mobile', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => true,'comment' => '手机号',])
			->addColumn('avatar', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '头像',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 正常 2 禁用',])
			->addColumn('password', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '密码',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
