<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsFormFields extends Migrator
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
        $table = $this->table('cms_form_fields', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '动态表单字段' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('form_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => 'form id',])
			->addColumn('label', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => true,'comment' => '字段 label',])
			->addColumn('name', 'string', ['limit' => 50,'null' => false,'default' => '','signed' => true,'comment' => '表单字段name',])
			->addColumn('default_value', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '默认值',])
			->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 1,'signed' => false,'comment' => '类型',])
			->addColumn('rule', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '验证规则',])
			->addColumn('length', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 0,'signed' => true,'comment' => '字段长度',])
			->addColumn('failed_message', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '验证失败信息',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 展示 2 隐藏',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
