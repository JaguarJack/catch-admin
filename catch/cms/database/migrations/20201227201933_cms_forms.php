<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsForms extends Migrator
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
        $table = $this->table('cms_forms', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '动态表单' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '表单名称',])
			->addColumn('alias', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '表单别名',])
			->addColumn('submit_url', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '表单提交的 URL',])
			->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '表单标题',])
			->addColumn('keywords', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '关键词',])
			->addColumn('description', 'string', ['limit' => 1000,'null' => false,'default' => '','signed' => true,'comment' => '描述',])
			->addColumn('success_message', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '成功提示信息',])
			->addColumn('failed_message', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '失败提示信息',])
			->addColumn('success_link_to', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '成功后跳转',])
			->addColumn('is_login_to_submit', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 需要 2 不需要',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
