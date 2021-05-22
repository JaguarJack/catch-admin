<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCategory extends Migrator
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
        $table = $this->table('cms_category', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '分类表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('name', 'string', ['limit' => 100,'null' => true,'signed' => true,'comment' => '栏目名称',])
			->addColumn('parent_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 0,'signed' => true,'comment' => '父级ID',])
			->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => 'seo标题',])
			->addColumn('keywords', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => 'seo关键词',])
			->addColumn('description', 'string', ['limit' => 1000,'null' => false,'default' => '','signed' => true,'comment' => '描述',])
			->addColumn('url', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '自定义 URL',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '状态',])
            ->addColumn('is_can_contribute', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '是否可以投稿',])
            ->addColumn('is_can_comment', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '是否可以评论',])
            ->addColumn('type', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '页面模式',])
            ->addColumn('weight', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 1,'signed' => true,'comment' => '权重',])
			// ->addColumn('is_link_out', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '1 是 2 否',])
			->addColumn('link_to', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '链接外部地址',])
            ->addColumn('limit', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 10,'signed' => true,'comment' => '每页数量',])
            ->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}
