<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsModelFields extends Migrator
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
        $table = $this->table('cms_model_fields', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '模型字段' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '字段中文名称',])
			->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '表单字段名称',])
            ->addColumn('type', 'string', ['limit' => 50, 'null' => false,'signed' => true,'comment' => '类型',])
            ->addColumn('length', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => true,'signed' => true,'comment' => '字段长度',])
            ->addColumn('default_value', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '默认值',])
            ->addColumn('options', 'string', ['limit' => 1000,'null' => false,'default' => '', 'comment' => '选项',])
            ->addColumn('is_index', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '是否是索引 1 是 2 否',])
            ->addColumn('is_unique', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '是否唯一 1 是 2 否',])
            ->addColumn('rules', 'string', ['limit' => 255,'null' => false,'default' => '', 'comment' => '验证规则',])
            ->addColumn('pattern', 'string', ['limit' => 255,'null' => false,'default' => '', 'comment' => '正则',])
            ->addColumn('model_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => true,'signed' => true,'comment' => '模型ID',])
			->addColumn('use_at_list', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '展示在列表 1 是 2 否',])
			->addColumn('use_at_detail', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '展示在详情 1 是 2 否',])
			->addColumn('use_at_search', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '用作是否搜索 1 是 2 否',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->addColumn('sort', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 1,'signed' => false,'comment' => '排序',])
            ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY,'null' => false,'default' => 1,'signed' => false,'comment' => '状态 1显示 2隐藏',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
